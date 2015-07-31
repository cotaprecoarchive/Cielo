<?php

namespace CotaPreco\Cielo\Unmarshalling\SimpleXml;

use CotaPreco\Cielo\Authorization;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class AuthorizationFromRootNodeTest extends TestCase
{
    /**
     * @test
     */
    public function invoke()
    {
        $root = <<<AUTHORIZATION
<autorizacao>
    <codigo>5</codigo>
    <mensagem>Autorização negada</mensagem>
    <data-hora>2011-12-09T10:58:45.847-02:00</data-hora>
    <valor>1000</valor>
    <lr>57</lr>
    <nsu>221766</nsu>
</autorizacao>
AUTHORIZATION;

        $authorization = new AuthorizationFromRootNode();

        $this->assertInstanceOf(
            Authorization::class,
            $authorization(new \SimpleXMLElement($root))
        );
    }
}
