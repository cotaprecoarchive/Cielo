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
final class Authentication
{
    /**
     * @var int
     */
    private $code;

    /**
     * @var string
     */
    private $message;

    /**
     * @var \DateTimeImmutable
     */
    private $ocurredAt;

    /**
     * @var int
     */
    private $value;

    /**
     * @var Eci
     */
    private $eci;

    /**
     * @param int                $code
     * @param string             $message
     * @param \DateTimeImmutable $ocurredAt
     * @param int                $value
     * @param Eci                $eci
     */
    public function __construct($code, $message, \DateTimeImmutable $ocurredAt, $value, Eci $eci)
    {
        $this->code      = (int) $code;
        $this->message   = (string) $message;
        $this->ocurredAt = $ocurredAt;
        $this->value     = (int) $value;
        $this->eci       = $eci;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getOcurredAt()
    {
        return $this->ocurredAt;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return Eci
     */
    public function getEci()
    {
        return $this->eci;
    }

    /**
     * @return bool
     */
    public function isAuthenticated()
    {
        return $this->checkBitmask($this->eci->getIndicator(), SecureIndicator::AUTHENTICATED);
    }

    /**
     * @return bool
     */
    public function isUnauthenticated()
    {
        return $this->checkBitmask($this->eci->getIndicator(), SecureIndicator::UNAUTHENTICATED);
    }

    /**
     * @return bool
     */
    public function isWithoutAuthentication()
    {
        return $this->checkBitmask($this->eci->getIndicator(), SecureIndicator::WITHOUT_AUTHENTICATION);
    }

    /**
     * @param  int $value
     * @param  int $bitmask
     * @return bool
     */
    private function checkBitmask($value, $bitmask)
    {
        return ($value & $bitmask) === $bitmask;
    }
}
