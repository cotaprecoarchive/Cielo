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

use CotaPreco\Cielo\RequestInterface;
use CotaPreco\Cielo\Serialization\Request\SerializerInterface;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
abstract class AbstractSerializer implements SerializerInterface
{
    /**
     * @return string
     */
    abstract protected function getRootNodeName();

    /**
     * @param  RequestInterface $request
     * @param  \DOMElement      $root
     * @return mixed
     */
    abstract protected function serialize(
        RequestInterface $request,
        \DOMElement $root
    );

    /**
     * @param  RequestInterface $request
     * @return string
     */
    public function __invoke(RequestInterface $request)
    {
        $document = new \DOMDocument('1.0', 'UTF-8');

        $document->formatOutput = true;

        $root = $document->createElement($this->getRootNodeName());

        $root->setAttribute('id', $request->getRequestId());

        $root->setAttribute('versao', $request->getShapeVersion());

        $this->serialize(
            $request,
            $root
        );

        $document->appendChild($root);

        return $document->saveXML(null, LIBXML_NOWARNING | LIBXML_NOBLANKS);
    }
}
