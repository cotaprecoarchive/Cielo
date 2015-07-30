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
final class Installments implements InstallmentsInterface
{
    /**
     * @var string
     */
    private $installments;

    /**
     * @param string $installments
     */
    private function __construct($installments)
    {
        $this->installments = (string) $installments;
    }

    /**
     * @param  int $installments
     * @return Installments
     * @throws \LogicException if the number of `$installments` is equal to 1
     */
    public static function fromNumberOfInstallments($installments)
    {
        if ((int) $installments === 1) {
            throw new \LogicException(
                sprintf(
                    'If the number of installments is 1, consider using `%s::ONE_TIME_PAYMENT` or `%s::DEBIT` instead',
                    PaymentProduct::class,
                    PaymentProduct::class
                )
            );
        }

        return new self(sprintf('%02d', $installments));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->installments;
    }
}
