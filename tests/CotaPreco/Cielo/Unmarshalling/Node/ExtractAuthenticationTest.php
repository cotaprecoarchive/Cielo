<?php

namespace CotaPreco\Cielo\Unmarshalling\Node;

use CotaPreco\Cielo\Authentication;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class ExtractAuthenticationTest extends TestCase
{
    /**
     * @test
     */
    public function invoke()
    {
        $authentication = <<<AUTENTICACAO
<autenticacao>
    <codigo>2</codigo>
    <mensagem>Autenticada com sucesso</mensagem>
    <data-hora>2015-01-01T00:00:00.000-02:00</data-hora>
    <valor>1000</valor>
    <eci>5</eci>
</autenticacao>
AUTENTICACAO;

        $extractAuth = new ExtractAuthentication();

        $authentication = $extractAuth(dom_import_simplexml(
            simplexml_load_string($authentication)
        ));

        $this->assertInstanceOf(Authentication::class, $authentication);

        /* @var Authentication $authentication */
        $this->assertSame(2, $authentication->getCode());
        $this->assertTrue($authentication->isAuthenticated());
    }
}
