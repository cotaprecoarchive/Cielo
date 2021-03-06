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

namespace CotaPreco\Cielo\Http;

use CotaPreco\Cielo\CieloEnvironment;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 * @codeCoverageIgnoreFile
 */
final class NativeCurlHttpClient implements CieloHttpClientInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke($environment, $xml)
    {
        $environmentUrl = 'https://ecommerce.cielo.com.br/servicos/ecommwsec.do';

        if ($environment === CieloEnvironment::DEVELOPMENT) {
            $environmentUrl = 'https://qasecommerce.cielo.com.br/servicos/ecommwsec.do';
        }

        $payload = http_build_query([
            'mensagem' => $xml
        ]);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $environmentUrl,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HEADER         => false,
            CURLOPT_SSLVERSION     => defined('CURL_SSLVERSION_TLSv1_0') ? CURL_SSLVERSION_TLSv1_0 : 4,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/x-www-form-urlencoded; charset="UTF-8"',
                sprintf('Content-Length: %d', strlen($payload)),
                'Accept: text/xml'
            ],
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT        => 30
        ]);

        return curl_exec($curl);
    }
}
