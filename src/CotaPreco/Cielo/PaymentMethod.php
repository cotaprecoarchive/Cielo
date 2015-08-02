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

use CotaPreco\Cielo\Serialization\AcceptsSerializationVisitor;
use CotaPreco\Cielo\Serialization\AcceptsSerializationVisitorTrait;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
final class PaymentMethod implements AcceptsSerializationVisitor
{
    use AcceptsSerializationVisitorTrait;

    /**
     * @var CardIssuer
     */
    private $issuer;

    /**
     * @var string
     */
    private $product;

    /**
     * @var InstallmentsInterface
     */
    private $installments;

    /**
     * @param CardIssuer            $issuer
     * @param string|int            $product
     * @param InstallmentsInterface $installments
     */
    private function __construct(CardIssuer $issuer, $product, InstallmentsInterface $installments)
    {
        $this->issuer       = $issuer;
        $this->product      = $product;
        $this->installments = $installments;
    }

    /**
     * @return CardIssuer
     */
    public function getCardIssuer()
    {
        return $this->issuer;
    }

    /**
     * @return string|int
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @return OneTimePaymentInstallments|Installments
     */
    public function getInstallments()
    {
        return $this->installments;
    }

    /**
     * @return int
     */
    public function getNumberOfInstallments()
    {
        return intval((string) $this->installments);
    }

    /**
     * @param  CardIssuer $issuer
     * @return PaymentMethod
     */
    public static function forIssuerAsOneTimePayment(CardIssuer $issuer)
    {
        return new self($issuer, PaymentProduct::ONE_TIME_PAYMENT, new OneTimePaymentInstallments());
    }

    /**
     * @param  CardIssuer $issuer
     * @return PaymentMethod
     */
    public static function forIssuerAsDebitPayment(CardIssuer $issuer)
    {
        return new self($issuer, PaymentProduct::DEBIT, new OneTimePaymentInstallments());
    }

    /**
     * @param  CardIssuer $issuer
     * @param  int        $installments
     * @return PaymentMethod
     */
    public static function forIssuerWithInstallmentsByMerchant(CardIssuer $issuer, $installments)
    {
        return new self(
            $issuer,
            PaymentProduct::INSTALLMENTS_BY_AFFILIATED_MERCHANTS,
            Installments::fromNumberOfInstallments($installments)
        );
    }

    /**
     * @param  CardIssuer $issuer
     * @param  int        $installments
     * @return PaymentMethod
     */
    public static function forIssuerWithInstallmentsByCardIssuers(CardIssuer $issuer, $installments)
    {
        return new self(
            $issuer,
            PaymentProduct::INSTALLMENTS_BY_CARD_ISSUERS,
            Installments::fromNumberOfInstallments($installments)
        );
    }
}
