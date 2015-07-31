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
final class TransactionWrappedToken
{
    /**
     * @var CardToken
     */
    private $token;

    /**
     * @var int
     */
    private $status;

    /**
     * @var string
     */
    private $truncatedCardNumber;

    /**
     * @param CardToken $token
     * @param int       $status
     * @param string    $truncatedCardNumber
     */
    public function __construct(CardToken $token, $status, $truncatedCardNumber)
    {
        $this->token               = $token;
        $this->status              = (int) $status;
        $this->truncatedCardNumber = $truncatedCardNumber;
    }

    /**
     * @return CardToken
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getTruncatedCardNumber()
    {
        return $this->truncatedCardNumber;
    }

    /**
     * @return bool
     */
    public function isBlocked()
    {
        return $this->status === GeneratedTokenStatus::BLOCKED;
    }

    /**
     * @return bool
     */
    public function isUnblocked()
    {
        return $this->status === GeneratedTokenStatus::UNBLOCKED;
    }
}
