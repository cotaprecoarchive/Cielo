<?php

namespace CotaPreco\Cielo\Unmarshalling\Node;

use CotaPreco\Cielo\Order;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class ExtractOrderTest extends TestCase
{
    /**
     * @test
     */
    public function invoke()
    {
        $extractOrder = new ExtractOrder();

        /* @var Order $order */
        $order = $extractOrder(dom_import_simplexml(
            simplexml_load_string(
                <<<DADOS_PEDIDO
<dados-pedido>
    <numero>178148599</numero>
    <valor>1000</valor>
    <moeda>986</moeda>
    <data-hora>2015-01-01T00:00:00</data-hora>
    <idioma>PT</idioma>
    <soft-descriptor/>
    <taxa-embarque/>
</dados-pedido>
DADOS_PEDIDO
            )
        ));

        $this->assertEquals('178148599', $order->getNumber());
        $this->assertNull($order->getDescription());
        $this->assertNull($order->getDescriptor());
    }
}
