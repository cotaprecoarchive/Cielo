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

namespace CotaPreco\Cielo\Serialization\Node;

use CotaPreco\Cielo\Order;
use CotaPreco\Cielo\Serialization\AcceptsSerializationVisitor;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
final class OrderVisitor extends AbstractNodeSerializationVisitor
{
    /**
     * {@inheritdoc}
     */
    public function visit(AcceptsSerializationVisitor $order)
    {
        $child = $this->document->createElement('dados-pedido');

        /* @var Order $order */
        $withValues = array_filter(
            [
                'numero'          => $order->getNumber(),
                'valor'           => $order->getValue(),
                'moeda'           => $order->getCurrency(),
                'data-hora'       => $order->getCreatedAt()->format('Y-m-d\TH:i:s'),
                'descricao'       => $order->getDescription(),
                'idioma'          => $order->getLanguage(),
                'soft-descriptor' => $order->getDescriptor(),
                'taxa-embarque'   => $order->getShipping()
            ]
        );

        /* @var string $value */
        foreach ($withValues as $name => $value) {
            $child->appendChild(
                $this->document->createElement(
                    $name,
                    $value
                )
            );
        }

        $this->root->appendChild($child);
    }
}
