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
final class CardHolder implements IdentifiesHolder
{
    /**
     * @var null|string
     */
    private $name;

    /**
     * @var CreditCard
     */
    private $card;

    /**
     * @param string     $name
     * @param CreditCard $card
     */
    private function __construct($name, CreditCard $card)
    {
        $this->name = $name;
        $this->card = $card;
    }

    /**
     * @param  string     $name
     * @param  CreditCard $card
     * @return self
     */
    public static function fromHolderNameAndCard($name, CreditCard $card)
    {
        return new self($name, $card);
    }

    /**
     * @param  CreditCard $card
     * @return self
     */
    public static function fromCard($card)
    {
        return new self(null, $card);
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return CreditCard
     */
    public function getCard()
    {
        return $this->card;
    }
}
