<?php

namespace CotaPreco\Cielo\Unmarshalling\Node;

use CotaPreco\Cielo\Capture;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class ExtractCaptureTest extends TestCase
{
    /**
     * @test
     */
    public function invoke()
    {
        $extractCapture = new ExtractCapture();

        /* @var Capture $capture */
        $capture = $extractCapture(dom_import_simplexml(
            simplexml_load_string(
                <<<CAPTURA
<captura>
    <codigo>6</codigo>
    <mensagem>Transacao capturada com sucesso</mensagem>
    <data-hora>2015-01-01T00:00:00.000-02:00</data-hora>
    <valor>900</valor>
    <taxa-embarque>900</taxa-embarque>
</captura>
CAPTURA
            )
        ));

        $this->assertSame(6, $capture->getCode());
    }
}
