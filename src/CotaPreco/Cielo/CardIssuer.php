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
final class CardIssuer
{
    /**
     * @var string
     */
    const VISA = 'visa';

    /**
     * @var string
     */
    const MASTERCARD = 'mastercard';

    /**
     * @var string
     */
    private $issuer;

    /**
     * @param string $issuer
     */
    private function __construct($issuer)
    {
        $this->issuer = $issuer;
    }

    /**
     * @param  string $issuer
     * @return CardIssuer
     */
    public static function fromIssuerString($issuer)
    {
        $allowed = self::getAllAllowedIssuers();

        if (! in_array($issuer, $allowed, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid type. Only `%s` are allowed. Got `%s`',
                    implode('`, `', $allowed),
                    $issuer
                )
            );
        }

        return new self($issuer);
    }

    /**
     * @return array
     */
    public static function getAllAllowedIssuers()
    {
        return [
            CardIssuer::VISA,
            CardIssuer::MASTERCARD
        ];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->issuer;
    }
}
