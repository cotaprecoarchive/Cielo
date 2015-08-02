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

namespace CotaPreco\Cielo\Unmarshalling\Node;

use CotaPreco\Cielo\CardIssuer;
use CotaPreco\Cielo\PaymentMethod;
use CotaPreco\Cielo\PaymentProduct;
use CotaPreco\Cielo\Unmarshalling\GetElementValueByTagName;
use DOMElement;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
final class ExtractPaymentMethod
{
    /**
     * @param  DOMElement $element
     * @return PaymentMethod|null
     */
    public function __invoke(DOMElement $element)
    {
        $getElementValue = GetElementValueByTagName::fromRootNode($element);

        $installments = (int) $getElementValue('parcelas');

        /* @var CardIssuer $issuer */
        $issuer       = CardIssuer::fromIssuerString($getElementValue('bandeira'));

        switch ($getElementValue('produto')) {
            case PaymentProduct::DEBIT:
                return PaymentMethod::forIssuerAsDebitPayment($issuer);

            case PaymentProduct::ONE_TIME_PAYMENT:
                return PaymentMethod::forIssuerAsOneTimePayment($issuer);

            case PaymentProduct::INSTALLMENTS_BY_AFFILIATED_MERCHANTS:
                return PaymentMethod::forIssuerWithInstallmentsByMerchant(
                    $issuer,
                    $installments
                );

            case PaymentProduct::INSTALLMENTS_BY_CARD_ISSUERS:
                return PaymentMethod::forIssuerWithInstallmentsByCardIssuers(
                    $issuer,
                    $installments
                );
        }

        return null;
    }
}
