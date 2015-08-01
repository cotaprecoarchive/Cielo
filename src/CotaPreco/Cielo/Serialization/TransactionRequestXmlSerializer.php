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

namespace CotaPreco\Cielo\Serialization;

use CotaPreco\Cielo\Request\TransactionRequest;
use CotaPreco\Cielo\RequestInterface;
use CotaPreco\Cielo\Serialization\Xml\CardHolderSerializationVisitor;
use CotaPreco\Cielo\Serialization\Xml\MerchantSerializationVisitor;
use CotaPreco\Cielo\Serialization\Xml\OrderSerializationVisitor;
use CotaPreco\Cielo\Serialization\Xml\PaymentMethodSerializationVisitor;
use CotaPreco\Cielo\Serialization\Xml\TransactionRequestSerializationVistor;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
final class TransactionRequestXmlSerializer extends AbstractXmlRequestSerializer
{
    /**
     * @return string
     */
    protected function getRootNodeName()
    {
        return 'requisicao-transacao';
    }

    /**
     * {@inheritdoc}
     * @param TransactionRequest $request
     */
    protected function writeRequestStructure(RequestInterface $request, \XMLWriter $writer)
    {
        $request->getMerchant()
            ->accept(new MerchantSerializationVisitor($writer));

        $request->getHolder()
            ->accept(new CardHolderSerializationVisitor($writer));

        $request->getOrder()
            ->accept(new OrderSerializationVisitor($writer));

        $request->getPaymentMethod()
            ->accept(new PaymentMethodSerializationVisitor($writer));

        $request->accept(new TransactionRequestSerializationVistor($writer));
    }
}
