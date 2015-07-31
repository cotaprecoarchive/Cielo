<?php

namespace CotaPreco\Cielo\Unmarshalling\SimpleXml;

use CotaPreco\Cielo\TransactionWrappedToken;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class WrappedTokenFromRootNodeTest extends TestCase
{
    /**
     * @test
     */
    public function invoke()
    {
        $root = <<<TOKEN
<dados-token>
    <codigo-token>TuS6LeBHWjqFFtE7S3zR052Jl/KUlD+tYJFpAdlA87E=</codigo-token>
    <status>1</status>
    <numero-cartao-truncado>455187******0183</numero-cartao-truncado>
</dados-token>
TOKEN;

        $token = new WrappedTokenFromRootNode();

        $this->assertInstanceOf(
            TransactionWrappedToken::class,
            $token(new \SimpleXMLElement($root))
        );
    }
}
