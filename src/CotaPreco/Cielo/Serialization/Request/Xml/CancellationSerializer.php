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

namespace CotaPreco\Cielo\Serialization\Request\Xml;

use CotaPreco\Cielo\Request\Cancellation\FullCancellation;
use CotaPreco\Cielo\Request\Cancellation\PartialCancellation;
use CotaPreco\Cielo\RequestInterface;
use CotaPreco\Cielo\Serialization\Node\MerchantVisitor;
use CotaPreco\Cielo\Serialization\Request\SerializerInterface;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
final class CancellationSerializer implements SerializerInterface
{
    /**
     * {@inheritdoc}
     */
    public function canSerialize(RequestInterface $request)
    {
        return (
            $request instanceof FullCancellation ||
            $request instanceof PartialCancellation
        );
    }

    /**
     * {@inheritdoc}
     * @param FullCancellation|PartialCancellation $request
     */
    public function __invoke(RequestInterface $request)
    {
        $document = new \DOMDocument('1.0', 'UTF-8');

        $document->formatOutput = true;

        $root = $document->createElement('requisicao-cancelamento');

        $root->setAttribute('id', $request->getRequestId());

        $root->setAttribute('versao', $request->getShapeVersion());

        $root->appendChild(
            $document->createElement(
                'tid',
                $request->getTransactionId()
            )
        );

        $request->getMerchant()
            ->accept(new MerchantVisitor($root));

        if ($request instanceof PartialCancellation) {
            $root->appendChild(
                $document->createElement(
                    'valor',
                    $request->getValue()
                )
            );
        }

        $document->appendChild($root);

        return $document->saveXML(null, LIBXML_NOWARNING | LIBXML_NOEMPTYTAG);
    }
}