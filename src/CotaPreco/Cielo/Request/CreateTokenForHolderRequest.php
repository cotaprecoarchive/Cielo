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

namespace CotaPreco\Cielo\Request;

use CotaPreco\Cielo\CardHolder;
use CotaPreco\Cielo\Merchant;
use Rhumsaa\Uuid\Uuid;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
final class CreateTokenForHolderRequest extends AbstractCieloRequest
{
    /**
     * @var Merchant
     */
    private $merchant;

    /**
     * @var CardHolder
     */
    private $holder;

    /**
     * @param Merchant   $merchant
     * @param CardHolder $holder
     */
    public function __construct(Merchant $merchant, CardHolder $holder)
    {
        $this->requestId = Uuid::uuid4();
        $this->merchant  = $merchant;
        $this->holder    = $holder;
    }

    /**
     * @return Merchant
     */
    public function getMerchant()
    {
        return $this->merchant;
    }

    /**
     * @return CardHolder
     */
    public function getHolder()
    {
        return $this->holder;
    }
}
