<?php

namespace CotaPreco\Cielo\Unmarshalling\Node;

use CotaPreco\Cielo\TransactionWrappedToken;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class ExtractWrappedTokenTest extends TestCase
{
    /**
     * @test
     */
    public function invoke()
    {
        $extractWrappedToken = new ExtractWrappedToken();

        /* @var TransactionWrappedToken $wrappedToken */
        $wrappedToken = $extractWrappedToken(dom_import_simplexml(
            simplexml_load_string(
                <<<DADOS_TOKEN
<dados-token>
    <codigo-token>TuS6LeBHWjqFFtE7S3zR052Jl/KUlD+tYJFpAdlA87E=</codigo-token>
    <status>1</status>
    <numero-cartao-truncado>455187******0183</numero-cartao-truncado>
</dados-token>
DADOS_TOKEN
            )
        ));

        $this->assertEquals(
            'TuS6LeBHWjqFFtE7S3zR052Jl/KUlD+tYJFpAdlA87E=',
            $wrappedToken->getToken()
        );

        $this->assertFalse($wrappedToken->isBlocked());
        $this->assertEquals('455187******0183', $wrappedToken->getTruncatedCardNumber());
    }
}
