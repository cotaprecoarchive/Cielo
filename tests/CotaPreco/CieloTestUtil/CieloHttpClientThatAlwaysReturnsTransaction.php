<?php

namespace CotaPreco\CieloTestUtil;

use CotaPreco\Cielo\Http\CieloHttpClientInterface;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
final class CieloHttpClientThatAlwaysReturnsTransaction implements CieloHttpClientInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke($environment, $xml)
    {
        return file_get_contents(
            __DIR__ . '/../CieloTestAssets/minimal-transaction.xml'
        );
    }
}
