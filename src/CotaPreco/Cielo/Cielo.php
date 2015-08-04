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

namespace CotaPreco\Cielo;

use CotaPreco\Cielo\Http\CieloErrorResponseInterceptor;
use CotaPreco\Cielo\Http\CieloHttpClientInterface;
use CotaPreco\Cielo\Http\NativeCurlHttpClient;
use CotaPreco\Cielo\Request\AuthorizeTransaction;
use CotaPreco\Cielo\Request\Cancellation\FullCancellation;
use CotaPreco\Cielo\Request\Cancellation\PartialCancellation;
use CotaPreco\Cielo\Request\Capture\FullCapture;
use CotaPreco\Cielo\Request\Capture\PartialCapture;
use CotaPreco\Cielo\Request\CreateTokenForHolder;
use CotaPreco\Cielo\Request\CreateTransaction;
use CotaPreco\Cielo\Request\SearchTransaction;
use CotaPreco\Cielo\Serialization\Request\CieloRequestSerializerInterface;
use CotaPreco\Cielo\Serialization\Request\DefaultCieloRequestSerializer;
use CotaPreco\Cielo\Unmarshalling\Transaction\TransactionUnmarshaller;
use CotaPreco\Cielo\Unmarshalling\Transaction\TransactionUnmarshallerInterface;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 */
final class Cielo
{
    /**
     * @var int
     */
    private $environment;

    /**
     * @var Merchant
     */
    private $merchant;

    /**
     * @var CieloHttpClientInterface
     */
    private $client;

    /**
     * @var CieloRequestSerializerInterface
     */
    private $requestSerializer;

    /**
     * @var TransactionUnmarshallerInterface
     */
    private $transactionUnmarshaller;

    /**
     * @param int                                   $environment
     * @param Merchant                              $merchant
     * @param CieloHttpClientInterface              $client
     * @param CieloRequestSerializerInterface|null  $requestSerializer
     * @param TransactionUnmarshallerInterface|null $transactionUnmarshaller
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function __construct(
        $environment,
        Merchant                         $merchant,
        CieloHttpClientInterface         $client,
        CieloRequestSerializerInterface  $requestSerializer = null,
        TransactionUnmarshallerInterface $transactionUnmarshaller = null
    ) {
        $this->environment             = $environment;
        $this->merchant                = $merchant;
        $this->client                  = $client;
        $this->requestSerializer       = $requestSerializer ?: new DefaultCieloRequestSerializer();
        $this->transactionUnmarshaller = $transactionUnmarshaller ?: new TransactionUnmarshaller();
    }

    /**
     * @param  int    $environment
     * @param  string $affiliationId
     * @param  string $affiliationKey
     * @return self
     */
    public static function createFromAffiliationIdAndKey($environment, $affiliationId, $affiliationKey)
    {
        return new self(
            $environment,
            Merchant::fromAffiliationIdAndKey(
                $affiliationId,
                $affiliationKey
            ),
            new CieloErrorResponseInterceptor(new NativeCurlHttpClient())
        );
    }

    /**
     * @param  TransactionId|string $transactionId
     * @param  int                  $value
     * @return Transaction
     */
    public function cancelTransactionPartially($transactionId, $value)
    {
        return $this->unmarshallTransaction(
            $this->performRequest(new PartialCancellation(
                TransactionId::fromString((string) $transactionId),
                $this->merchant,
                $value
            ))
        );
    }

    /**
     * @param  TransactionId|string $transactionId
     * @return Transaction
     */
    public function cancelTransaction($transactionId)
    {
        return $this->unmarshallTransaction(
            $this->performRequest(new FullCancellation(
                TransactionId::fromString((string) $transactionId),
                $this->merchant
            ))
        );
    }

    /**
     * @param  TransactionId|string $transactionId
     * @return Transaction
     */
    public function getTransactionById($transactionId)
    {
        return $this->unmarshallTransaction(
            $this->performRequest(new SearchTransaction(
                TransactionId::fromString((string) $transactionId),
                $this->merchant
            ))
        );
    }

    /**
     * @param  TransactionId|string $transactionId
     * @param  int                  $value
     * @param  null|int             $shipping
     * @return Transaction
     */
    public function capturePartially($transactionId, $value, $shipping = null)
    {
        return $this->unmarshallTransaction(
            $this->performRequest(new PartialCapture(
                TransactionId::fromString((string) $transactionId),
                $this->merchant,
                $value,
                $shipping
            ))
        );
    }

    /**
     * @param  TransactionId|string $transactionId
     * @return Transaction
     */
    public function capture($transactionId)
    {
        return $this->unmarshallTransaction(
            $this->performRequest(new FullCapture(
                TransactionId::fromString((string) $transactionId),
                $this->merchant
            ))
        );
    }

    /**
     * @param  TransactionId|string $transactionId
     * @return Transaction
     */
    public function authorize($transactionId)
    {
        return $this->unmarshallTransaction(
            $this->performRequest(new AuthorizeTransaction(
                TransactionId::fromString((string) $transactionId),
                $this->merchant
            ))
        );
    }

    /**
     * @codeCoverageIgnore
     * @param  CardHolder $holder
     * @return Transaction
     */
    public function createTokenForHolder(CardHolder $holder)
    {
        $this->performRequest(new CreateTokenForHolder(
            $this->merchant,
            $holder
        ));
    }

    /**
     * @param  IdentifiesHolder $holder
     * @param  Order            $order
     * @param  PaymentMethod    $paymentMethod
     * @param  bool             $capture
     * @param  bool|false       $generateToken
     * @param  null|string      $returnUrl
     * @return Transaction
     */
    public function createAndAuthorizeWithoutAuthentication(
        IdentifiesHolder $holder,
        Order $order,
        PaymentMethod $paymentMethod,
        $capture,
        $generateToken = false,
        $returnUrl = null
    ) {
        return $this->unmarshallTransaction(
            $this->performRequest(CreateTransaction::authorizeWithoutAuthentication(
                $this->merchant,
                $holder,
                $order,
                $paymentMethod,
                $capture,
                $returnUrl,
                $generateToken
            ))
        );
    }

    /**
     * @param  IdentifiesHolder $holder
     * @param  Order            $order
     * @param  PaymentMethod    $paymentMethod
     * @param  bool             $capture
     * @param  string           $returnUrl
     * @param  bool|false       $generateToken
     * @return Transaction
     */
    public function createAndAuthenticateOnly(
        IdentifiesHolder $holder,
        Order $order,
        PaymentMethod $paymentMethod,
        $capture,
        $returnUrl,
        $generateToken = false
    ) {
        return $this->unmarshallTransaction(
            $this->performRequest(CreateTransaction::authenticateOnly(
                $this->merchant,
                $holder,
                $order,
                $paymentMethod,
                $capture,
                $returnUrl,
                $generateToken
            ))
        );
    }

    /**
     * @param  IdentifiesHolder $holder
     * @param  Order            $order
     * @param  PaymentMethod    $paymentMethod
     * @param  string           $returnUrl
     * @param  bool|false       $generateToken
     * @return Transaction
     */
    public function createAndAuthorizeOnly(
        IdentifiesHolder $holder,
        Order $order,
        PaymentMethod $paymentMethod,
        $returnUrl,
        $generateToken = false
    ) {
        return $this->unmarshallTransaction(
            $this->performRequest(CreateTransaction::authorizeOnly(
                $this->merchant,
                $holder,
                $order,
                $paymentMethod,
                false,
                $returnUrl,
                $generateToken
            ))
        );
    }

    /**
     * @param  IdentifiesHolder $holder
     * @param  Order            $order
     * @param  PaymentMethod    $paymentMethod
     * @param  bool             $capture
     * @param  bool|false       $generateToken
     * @param  null|string      $returnUrl
     * @return Transaction
     */
    public function createAndAuthorizeOnlyIfAuthenticated(
        IdentifiesHolder $holder,
        Order $order,
        PaymentMethod $paymentMethod,
        $capture,
        $generateToken = false,
        $returnUrl = null
    ) {
        return $this->unmarshallTransaction(
            $this->performRequest(CreateTransaction::authorizeOnlyIfAuthenticated(
                $this->merchant,
                $holder,
                $order,
                $paymentMethod,
                $capture,
                $returnUrl,
                $generateToken
            ))
        );
    }

    /**
     * @param  RequestInterface $request
     * @return string
     */
    private function performRequest(RequestInterface $request)
    {
        /* @var callable $requestSerializer */
        $requestSerializer = $this->requestSerializer;

        /* @var callable $client */
        $client            = $this->client;

        return $client(
            $this->environment,
            $requestSerializer($request)
        );
    }

    /**
     * @param  string $responseXml
     * @return Transaction
     */
    private function unmarshallTransaction($responseXml)
    {
        /* @var callable $unmarshaller */
        $unmarshaller = $this->transactionUnmarshaller;

        return $unmarshaller($responseXml);
    }

    /**
     * @return array
     * @codeCoverageIgnore
     */
    public function __debugInfo()
    {
        return [
            'environment'    => $this->environment === CieloEnvironment::DEVELOPMENT ? 'DEVELOPMENT' : 'PRODUCTION',
            'affiliationId'  => $this->merchant->getAffiliationId(),
            'affiliationKey' => $this->merchant->getAffiliationKey()
        ];
    }
}
