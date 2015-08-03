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

namespace CotaPreco\Cielo\Serialization\Request;

use CotaPreco\Cielo\RequestInterface;
use CotaPreco\Cielo\Serialization\Exception\SerializerNotFoundException;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
abstract class SerializerChain implements CieloRequestSerializerInterface
{
    /**
     * @var SerializerInterface[]
     */
    private $serializers;

    /**
     * @param SerializerInterface ...$serializers
     */
    protected function __construct(SerializerInterface ...$serializers)
    {
        $this->serializers = $serializers;
    }

    /**
     * @param  RequestInterface $request
     * @return string
     * @throws SerializerNotFoundException if there's no serializer in the chain that could handle serialization of
     * `$request`
     */
    public function __invoke(RequestInterface $request)
    {
        $candidates = array_filter(
            $this->serializers,
            function (SerializerInterface $serializer) use ($request) {
                return $serializer->canSerialize($request);
            }
        );

        $serializer = current($candidates);

        if (! $serializer) {
            throw SerializerNotFoundException::forRequest($request);
        }

        /* @var callable $serializer */
        return $serializer($request);
    }
}
