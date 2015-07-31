<?php

namespace CotaPreco\Cielo\Unmarshalling\SimpleXml;

use CotaPreco\Cielo\Capture;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class CaptureFromRootNodeTest extends TestCase
{
    /**
     * @test
     */
    public function invoke()
    {
        $root = <<<CAPTURE
<captura>
    <codigo>6</codigo>
    <mensagem>Transacao capturada com sucesso</mensagem>
    <data-hora>2011-12-08T14:23:08.779-02:00</data-hora>
    <valor>900</valor>
    <taxa-embarque>900</taxa-embarque>
</captura>
CAPTURE;

        $capture = new CaptureFromRootNode();

        $this->assertInstanceOf(
            Capture::class,
            $capture(new \SimpleXMLElement($root))
        );
    }
}
