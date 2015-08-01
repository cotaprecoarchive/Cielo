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
final class Bin
{
    /**
     * @var string
     */
    private $bin;

    /**
     * @param string $bin
     */
    private function __construct($bin)
    {
        $this->bin = (string) $bin;
    }

    /**
     * @param  string $binString
     * @return self
     */
    public static function fromString($binString)
    {
        if (! (is_numeric($binString) && strlen($binString) === 6)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Is not a valid BIN (Bank Identification Number): `%s`. A valid BIN contains exactly 6 digits',
                    $binString
                )
            );
        }

        return new self($binString);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->bin;
    }
}
