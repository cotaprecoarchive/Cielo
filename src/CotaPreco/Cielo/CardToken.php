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

use CotaPreco\Cielo\Serialization\AcceptsSerializationVisitorTrait;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
final class CardToken implements IdentifiesHolder
{
    use AcceptsSerializationVisitorTrait;

    /**
     * @var string
     */
    private $token;

    /**
     * @param string $token
     */
    public function __construct($token)
    {
        $this->token = (string) $token;
    }

    /**
     * @param  string $tokenString
     * @return self
     * @throws \InvalidArgumentException if `$tokenString` is empty or exceeds 100 characters
     */
    public static function fromString($tokenString)
    {
        if (empty($tokenString) || strlen($tokenString) > 100) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Is not a valid token: `%s`. A valid token should contain between 1 and 100 characters',
                    $tokenString
                )
            );
        }

        return new self($tokenString);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->token;
    }
}
