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

namespace CotaPreco\Cielo\Unmarshalling\GeneratedToken;

use CotaPreco\Cielo\CardToken;
use CotaPreco\Cielo\GeneratedToken;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
final class GeneratedTokenUnmarshaller implements GeneratedTokenUnmarshallerInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke($xml)
    {
        $document = new \DOMDocument('1.0');

        $document->loadXML($xml, LIBXML_NOWARNING);

        return new GeneratedToken(
            CardToken::fromString(
                $document->getElementsByTagName('codigo-token')->item(0)->nodeValue
            ),
            $document->getElementsByTagName('status')->item(0)->nodeValue,
            $document->getElementsByTagName('numero-cartao-truncado')->item(0)->nodeValue
        );
    }
}
