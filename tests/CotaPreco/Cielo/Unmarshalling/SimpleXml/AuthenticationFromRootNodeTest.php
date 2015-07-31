<?php

namespace CotaPreco\Cielo\Unmarshalling\SimpleXml;

use CotaPreco\Cielo\Authentication;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class AuthenticationFromRootNodeTest extends TestCase
{
    /**
     * @test
     */
    public function invoke()
    {
        $root = <<<AUTHENTICATION
<autenticacao>
    <codigo>2</codigo>
    <mensagem>Autenticada com sucesso</mensagem>
    <data-hora>2011-12-08T10:44:47.311-02:00</data-hora>
    <valor>1000</valor>
    <eci>5</eci>
</autenticacao>
AUTHENTICATION;

        $authentication = new AuthenticationFromRootNode();

        $this->assertInstanceOf(
            Authentication::class,
            $authentication(new \SimpleXMLElement($root))
        );
    }
}
