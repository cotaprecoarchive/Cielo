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
     * @var CardSecurityCode|null
     */
    private $securityCode;

    /**
     * @param string                $number
     * @param CreditCardExpiration  $expiration
     * @param int                   $indicator
     * @param CardSecurityCode|null $securityCode
     */
    private function __construct(
        $number,
        CreditCardExpiration $expiration,
        $indicator = CardSecurityCodeIndicator::WITHOUT_SECURITY_CODE,
        CardSecurityCode $securityCode = null
    ) {
        $this->number       = (string) $number;
        $this->expiration   = $expiration;
        $this->indicator    = (int) $indicator;
        $this->securityCode = $securityCode;
    }

    /**
     * @param  string               $number
     * @param  CreditCardExpiration $expiration
     * @return self
     */
    public static function createWithoutSecurityCode($number, CreditCardExpiration $expiration)
    {
        return new self($number, $expiration, CardSecurityCodeIndicator::WITHOUT_SECURITY_CODE);
    }

    /**
     * @param  string               $number
     * @param  CreditCardExpiration $expiration
     * @param  CardSecurityCode     $securityCode
     * @return self
     */
    public static function createWithSecurityCode(
        $number,
        CreditCardExpiration $expiration,
        CardSecurityCode $securityCode
    ) {
        return new self(
            $number,
            $expiration,
            CardSecurityCodeIndicator::WITH_SECURITY_CODE,
            $securityCode
        );
    }

    /**
     * @param  string               $number
     * @param  CreditCardExpiration $expiration
     * @return self
     */
    public static function createWithUnreadableSecurityCode($number, CreditCardExpiration $expiration)
    {
        return new self($number, $expiration, CardSecurityCodeIndicator::SECURITY_CODE_UNREADABLE);
    }

    /**
     * @param  string               $number
     * @param  CreditCardExpiration $expiration
     * @return self
     */
    public static function createWithInexistentSecurityCode($number, CreditCardExpiration $expiration)
    {
        return new self($number, $expiration, CardSecurityCodeIndicator::INEXISTENT_SECURITY_CODE);
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
     * @return CardSecurityCode|null
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
}
