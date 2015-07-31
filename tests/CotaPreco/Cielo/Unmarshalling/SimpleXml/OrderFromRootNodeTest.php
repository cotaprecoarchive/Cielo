<?php

namespace CotaPreco\Cielo\Unmarshalling\SimpleXml;

use CotaPreco\Cielo\Order;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class OrderFromRootNodeTest extends TestCase
{
    /**
     * @test
     */
    public function invoke()
    {
        $root = <<<ORDER
<dados-pedido>
    <numero>178148599</numero>
    <valor>1000</valor>
    <moeda>986</moeda>
    <data-hora>2011-12-07T11:43:37</data-hora>
    <descricao>[origem:10.50.54.156]</descricao>
    <idioma>PT</idioma>
    <soft-descriptor/>
    <taxa-embarque/>
</dados-pedido>
ORDER;

        $order = new OrderFromRootNode();

        $this->assertInstanceOf(Order::class, $order(new \SimpleXMLElement($root)));
    }
}
