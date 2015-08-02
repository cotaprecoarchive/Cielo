<?php

namespace CotaPreco\Cielo\Unmarshalling\Node;

use CotaPreco\Cielo\Authorization;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class ExtractAuthorizationTest extends TestCase
{
    /**
     * @test
     */
    public function invoke()
    {
        $extractAuthz = new ExtractAuthorization();

        /* @var Authorization $authorization */
        $authorization = $extractAuthz(dom_import_simplexml(
            simplexml_load_string(
                <<<AUTORIZACAO
<autorizacao>
    <codigo>5</codigo>
    <mensagem>Autorização negada</mensagem>
    <data-hora>2015-01-01T00:00:00.000-02:00</data-hora>
    <valor>1000</valor>
    <lr>57</lr>
    <nsu>221766</nsu>
</autorizacao>
AUTORIZACAO
            )
        ));

        $this->assertSame(5, $authorization->getCode());
        $this->assertEquals(57, $authorization->getLr());
    }
}
