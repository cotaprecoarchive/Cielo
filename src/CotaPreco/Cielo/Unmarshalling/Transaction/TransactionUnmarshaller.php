<?php

/*
 * Copyright (c) 2015 Cota Preço
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace CotaPreco\Cielo\Unmarshalling\Transaction;

use CotaPreco\Cielo\Authentication;
use CotaPreco\Cielo\Authorization;
use CotaPreco\Cielo\Cancellation;
use CotaPreco\Cielo\Capture;
use CotaPreco\Cielo\CardIssuer;
use CotaPreco\Cielo\CardToken;
use CotaPreco\Cielo\Eci;
use CotaPreco\Cielo\GeneratedToken;
use CotaPreco\Cielo\Order;
use CotaPreco\Cielo\Pan;
use CotaPreco\Cielo\PaymentMethod;
use CotaPreco\Cielo\PaymentProduct;
use CotaPreco\Cielo\Transaction;
use CotaPreco\Cielo\TransactionId;
use DateTimeImmutable;
use DOMDocument;
use DOMElement;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class TransactionUnmarshaller implements TransactionUnmarshallerInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke($xml)
    {
        $document = new DOMDocument('1.0', 'UTF-8');

        $document->loadXML($xml, LIBXML_NOWARNING);

        /* @var DOMElement $root */
        $root = $document->documentElement;

        /* @noinspection PhpParamsInspection */
        $transaction = new Transaction(
            TransactionId::fromString($this->getElementValue($root, 'tid')),
            Pan::fromTokenString($this->getElementValue($root, 'pan')),
            $this->extractOrder($document->getElementsByTagName('dados-pedido')->item(0)),
            $this->extractPaymentMethod($document->getElementsByTagName('forma-pagamento')->item(0)),
            $this->getElementValue($root, 'status'),
            $this->extractGeneratedToken($root)
        );

        $this->authenticateTransaction($root, $transaction);
        $this->authorizeTransaction($root, $transaction);
        $this->captureTransaction($root, $transaction);
        $this->cancelTransaction($root, $transaction);

        return $transaction;
    }

    /**
     * @param  DOMElement $root
     * @param  string     $name
     * @return string|null
     */
    private function getElementValue(DOMElement $root, $name)
    {
        $node = $root->getElementsByTagName($name)->item(0);

        if ($node) {
            return $node->nodeValue;
        }

        return null;
    }

    /**
     * @param  DOMElement $element
     * @return Order
     */
    private function extractOrder(DOMElement $element)
    {
        /* @noinspection PhpParamsInspection */
        return Order::fromPreviouslyIssuedOrder(
            $this->getElementValue($element, 'numero'),
            $this->getElementValue($element, 'valor'),
            new DateTimeImmutable($this->getElementValue($element, 'data-hora')),
            $this->getElementValue($element, 'moeda'),
            $this->getElementValue($element, 'descricao'),
            $this->getElementValue($element, 'idioma')
        );
    }

    /**
     * @param  DOMElement $element
     * @return PaymentMethod|null
     */
    private function extractPaymentMethod(DOMElement $element)
    {
        $issuer = CardIssuer::fromIssuerString($this->getElementValue($element, 'bandeira'));

        $installments = (int) $this->getElementValue($element, 'parcelas');

        switch ($this->getElementValue($element, 'produto')) {
            case PaymentProduct::DEBIT:
                return PaymentMethod::forIssuerAsDebitPayment($issuer);

            case PaymentProduct::ONE_TIME_PAYMENT:
                return PaymentMethod::forIssuerAsOneTimePayment($issuer);

            case PaymentProduct::INSTALLMENTS_BY_AFFILIATED_MERCHANTS:
                return PaymentMethod::forIssuerWithInstallmentsByMerchant($issuer, $installments);

            case PaymentProduct::INSTALLMENTS_BY_CARD_ISSUERS:
                return PaymentMethod::forIssuerWithInstallmentsByCardIssuers(
                    $issuer,
                    $installments
                );
        }

        return null;
    }

    /**
     * @param  DOMElement $root
     * @return GeneratedToken|null
     */
    private function extractGeneratedToken(DOMElement $root)
    {
        /* @var null|DOMElement $token */
        $token = $root->getElementsByTagName('token')->item(0);

        if (is_null($token)) {
            return null;
        }

        return new GeneratedToken(
            CardToken::fromString(
                $this->getElementValue($token, 'codigo-token')
            ),
            $this->getElementValue($token, 'status'),
            $this->getElementValue($token, 'numero-cartao-truncado')
        );
    }

    /**
     * @param  DOMElement  $root
     * @param  Transaction $transaction
     * @return string
     */
    private function authenticateTransaction(DOMElement $root, Transaction $transaction)
    {
        /* @var null|DOMElement $authentication */
        $authentication = $root->getElementsByTagName('autenticacao')->item(0);

        if (is_null($authentication)) {
            return null;
        }

        $transaction->authenticate(
            new Authentication(
                $this->getElementValue($authentication, 'codigo'),
                $this->getElementValue($authentication, 'mensagem'),
                new DateTimeImmutable($this->getElementValue($authentication, 'data-hora')),
                $this->getElementValue($authentication, 'valor'),
                Eci::fromIndicator((string) $this->getElementValue($authentication, 'eci'))
            )
        );
    }

    /**
     * @param  DOMElement  $root
     * @param  Transaction $transaction
     * @return null
     */
    private function authorizeTransaction(DOMElement $root, Transaction $transaction)
    {
        /* @var null|DOMElement $authorization */
        $authorization = $root->getElementsByTagName('autorizacao')->item(0);

        if (is_null($authorization)) {
            return null;
        }

        $transaction->authorize(
            new Authorization(
                $this->getElementValue($authorization, 'codigo'),
                $this->getElementValue($authorization, 'mensagem'),
                new DateTimeImmutable($this->getElementValue($authorization, 'data-hora')),
                $this->getElementValue($authorization, 'valor'),
                $this->getElementValue($authorization, 'lr'),
                $this->getElementValue($authorization, 'nsu')
            )
        );
    }

    /**
     * @param  DOMElement $root
     * @param  Transaction $transaction
     * @return null
     */
    private function captureTransaction(DOMElement $root, Transaction $transaction)
    {
        /* @var null|DOMElement $capture */
        $capture = $root->getElementsByTagName('captura')->item(0);

        if (is_null($capture)) {
            return null;
        }

        $transaction->capture(
            new Capture(
                $this->getElementValue($capture, 'codigo'),
                $this->getElementValue($capture, 'mensagem'),
                new DateTimeImmutable($this->getElementValue($capture, 'data-hora')),
                $this->getElementValue($capture, 'valor'),
                $this->getElementValue($capture, 'taxa-embarque')
            )
        );
    }

    /**
     * @param DOMElement  $root
     * @param Transaction $transaction
     */
    private function cancelTransaction(DOMElement $root, Transaction $transaction)
    {
        $cancellations = $root->getElementsByTagName('cancelamentos');

        /* @var DOMElement $cancellation */
        foreach ($cancellations as $cancellation) {
            $transaction->cancel(
                new Cancellation(
                    $this->getElementValue($cancellation, 'codigo'),
                    $this->getElementValue($cancellation, 'mensagem'),
                    new DateTimeImmutable($this->getElementValue($cancellation, 'data-hora')),
                    $this->getElementValue($cancellation, 'valor')
                )
            );
        }
    }
}
