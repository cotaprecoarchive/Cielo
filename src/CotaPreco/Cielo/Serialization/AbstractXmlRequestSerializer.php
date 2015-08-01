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

use CotaPreco\Cielo\RequestInterface;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
abstract class AbstractXmlRequestSerializer
{
    /**
     * @param  RequestInterface $request
     * @return string
     */
    public function __invoke(RequestInterface $request)
    {
        $writer = new \XMLWriter();

        $writer->openMemory();

        $writer->setIndent(true);

        $writer->startDocument('1.0', 'UTF-8');

        $writer->startElement($this->getRootNodeName());

        $writer->writeAttribute('id', $request->getRequestId());

        $writer->writeAttribute('versao', $request->getShapeVersion());

        $this->writeRequestStructure($request, $writer);

        $writer->endElement();

        return $writer->outputMemory(true);
    }

    /**
     * @param  RequestInterface $request
     * @param  \XMLWriter       $writer
     * @return mixed
     */
    abstract protected function writeRequestStructure(
        RequestInterface $request,
        \XMLWriter $writer
    );

    /**
     * @return string
     */
    abstract protected function getRootNodeName();
}
