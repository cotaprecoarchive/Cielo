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

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
final class CreditCard
{
    /**
     * @var string
     */
    private $number;

    /**
     * @var CreditCardExpiration
     */
    private $expiration;

    /**
     * @var int
     */
    private $indicator;

    /**
     * @var Cvv|null
     */
    private $securityCode;

    /**
     * @param string               $number
     * @param CreditCardExpiration $expiration
     * @param int                  $indicator
     * @param Cvv|null             $cvv
     */
    private function __construct(
        $number,
        CreditCardExpiration $expiration,
        $indicator = CardSecurityCodeIndicator::WITHOUT_SECURITY_CODE,
        Cvv $cvv = null
    ) {
        $this->number       = (string) $number;
        $this->expiration   = $expiration;
        $this->indicator    = (int) $indicator;
        $this->securityCode = $cvv;
    }

    /**
     * @param  string $number
     * @param  int    $year
     * @param  int    $month
     * @return CreditCard
     */
    public static function createWithoutSecurityCode($number, $year, $month)
    {
        return new self(
            $number,
            CreditCardExpiration::fromYearAndMonth($year, $month),
            CardSecurityCodeIndicator::WITHOUT_SECURITY_CODE
        );
    }

    /**
     * @param  string $number
     * @param  int    $year
     * @param  int    $month
     * @param  string $cvv
     * @return self
     */
    public static function createWithSecurityCode($number, $year, $month, $cvv)
    {
        return new self(
            $number,
            CreditCardExpiration::fromYearAndMonth($year, $month),
            CardSecurityCodeIndicator::WITH_SECURITY_CODE,
            Cvv::fromVerificationValue((string) $cvv)
        );
    }

    /**
     * @param  string $number
     * @param  int    $year
     * @param  int    $month
     * @return CreditCard
     */
    public static function createWithUnreadableSecurityCode($number, $year, $month)
    {
        return new self(
            $number,
            CreditCardExpiration::fromYearAndMonth($year, $month),
            CardSecurityCodeIndicator::SECURITY_CODE_UNREADABLE
        );
    }

    /**
     * @param  string $number
     * @param  int    $year
     * @param  int    $month
     * @return CreditCard
     */
    public static function createWithInexistentSecurityCode($number, $year, $month)
    {
        return new self(
            $number,
            CreditCardExpiration::fromYearAndMonth($year, $month),
            CardSecurityCodeIndicator::INEXISTENT_SECURITY_CODE
        );
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return CreditCardExpiration
     */
    public function getExpiration()
    {
        return $this->expiration;
    }

    /**
     * @return Cvv|null
     */
    public function getSecurityCode()
    {
        return $this->securityCode;
    }

    /**
     * @return int
     */
    public function getSecurityCodeIndicator()
    {
        return $this->indicator;
    }

    /**
     * @return Bin
     */
    public function getBin()
    {
        return Bin::fromString(substr($this->number, 0, 6));
    }
}
