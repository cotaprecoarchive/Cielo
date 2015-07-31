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
final class Eci
{
    /**
     * @var int
     */
    private $indicator;

    /**
     * @param int $indicator
     */
    private function __construct($indicator)
    {
        $this->indicator = $indicator;
    }

    /**
     * @param  string $indicator
     * @return Eci
     */
    public static function fromIndicator($indicator)
    {
        switch ((int) $indicator) {
            case 5:
            case 2:
                return new self(SecureIndicator::AUTHENTICATED);

            case 6:
            case 1:
                return new self(SecureIndicator::WITHOUT_AUTHENTICATION);

            case 7:
            case 0:
            default:
                return new self(
                    SecureIndicator::UNAUTHENTICATED | SecureIndicator::MERCHANT_DID_NOT_SEND_AUTHENTICATION
                );
        }
    }

    /**
     * @see SecureIndicator
     * @return int
     */
    public function getIndicator()
    {
        return $this->indicator;
    }
}
