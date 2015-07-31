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
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
final class Authorization
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
     * @var string
     */
    private $lr;

    /**
     * @var string
     */
    private $nsu;

    /**
     * @param int                $code
     * @param string             $message
     * @param \DateTimeImmutable $ocurredAt
     * @param int                $value
     * @param string             $lr
     * @param string             $nsu
     */
    public function __construct($code, $message, \DateTimeImmutable $ocurredAt, $value, $lr, $nsu)
    {
        $this->code      = (int) $code;
        $this->message   = (string) $message;
        $this->ocurredAt = $ocurredAt;
        $this->value     = (int) $value;
        $this->lr        = (string) $lr;
        $this->nsu       = (string) $nsu;
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
     * @return string
     */
    public function getLr()
    {
        return $this->lr;
    }

    /**
     * @return string
     */
    public function getNsu()
    {
        return $this->nsu;
    }
}
