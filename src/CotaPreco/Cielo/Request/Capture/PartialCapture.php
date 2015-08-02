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

namespace CotaPreco\Cielo\Request\Capture;

use CotaPreco\Cielo\Merchant;
use CotaPreco\Cielo\TransactionId;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
final class PartialCapture extends AbstractCaptureRequest
{
    /**
     * @var int
     */
    private $value;

    /**
     * @var null|int
     */
    private $shipping;

    /**
     * @param TransactionId $transactionId
     * @param Merchant      $merchant
     * @param int           $value
     * @param null|int      $shipping
     */
    public function __construct(
        TransactionId $transactionId,
        Merchant $merchant,
        $value,
        $shipping = null
    ) {
        parent::__construct($transactionId, $merchant);

        $this->value    = $value;
        $this->shipping = $shipping ? (int) $shipping : null;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return int|null
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * @return bool
     */
    public function hasShipping()
    {
        return ! is_null($this->shipping);
    }
}
