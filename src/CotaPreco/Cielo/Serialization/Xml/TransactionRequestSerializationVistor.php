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

namespace CotaPreco\Cielo\Serialization\Xml;

use CotaPreco\Cielo\CardHolder;
use CotaPreco\Cielo\Serialization\AcceptsSerializationVisitor;
use CotaPreco\Cielo\TransactionRequest;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
final class TransactionRequestSerializationVistor extends AbstractXmlWriterSerializationVisitor
{
    /**
     * {@inheritdoc}
     * @param TransactionRequest $request
     */
    public function visit(AcceptsSerializationVisitor $request)
    {
        $request->getMerchant()
            ->accept(new MerchantSerializationVisitor($this->writer));

        $request->getHolder()
            ->accept(new CardHolderSerializationVisitor($this->writer));

        $request->getOrder()
            ->accept(new OrderSerializationVisitor($this->writer));

        $request->getPaymentMethod()
            ->accept(new PaymentMethodSerializationVisitor($this->writer));

        $bin = null;

        if ($request->getHolder() instanceof CardHolder) {
            /* @var CardHolder $holder */
            $holder = $request->getHolder();

            $bin    = (string) $holder->getCard()->getBin();
        }

        $this->writeAllValues(
            array_filter(
                [
                    'url-retorno' => $request->getReturnUrl(),
                    'autorizar'   => (string) $request->getAuthorizeIndicator(),
                    'capturar'    => $request->shouldCapture() ? 'true' : 'false',
                    'bin'         => $bin,
                    'gerar-token' => $request->shouldGenerateToken() ? 'true' : 'false'
                ],
                function ($value) {
                    return ! (
                        is_null($value) || $value === false
                    );
                }
            )
        );
    }
}
