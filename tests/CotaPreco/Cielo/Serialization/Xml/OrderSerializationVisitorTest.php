<?php

namespace CotaPreco\Cielo\Serialization\Xml;

use CotaPreco\Cielo\CieloLanguage;
use CotaPreco\Cielo\Currency;
use CotaPreco\Cielo\Order;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class OrderSerializationVisitorTest extends TestCase
{
    /**
     * @test
     */
    public function visit()
    {
        /* @var \PHPUnit_Framework_MockObject_MockObject|\XMLWriter $writer */
        $writer = $this->getMock(\XMLWriter::class);

        $writer->expects($this->once())
            ->method('startElement')
            ->with($this->equalTo('dados-pedido'));

        $writer->expects($this->exactly(5))
            ->method('writeElement')
            ->withConsecutive(
                [
                    $this->equalTo('numero'),
                    $this->equalTo('1234')
                ],
                [
                    $this->equalTo('valor'),
                    $this->equalTo(50000)
                ],
                [
                    $this->equalTo('moeda'),
                    $this->equalTo(Currency::REAL)
                ],
                [
                    $this->equalTo('data-hora'),
                    $this->isType('string')
                ],
                [
                    $this->equalTo('idioma'),
                    $this->equalTo(CieloLanguage::PORTUGUESE)
                ]
            );

        $writer->expects($this->once())
            ->method('endElement');

        $visitor = new OrderSerializationVisitor($writer);

        $order = Order::fromOrderNumberAndValue('1234', 50000);

        $visitor->visit($order);
    }

    /**
     * @return Order[][]
     */
    public function provideDifferentOrders()
    {
        return [
            [
                Order::fromOrderNumberAndValue('1234', 10000),
                <<<XML
<dados-pedido>
    <numero>1234</numero>
    <valor>10000</valor>
    <moeda>986</moeda>
    <data-hora>%s</data-hora>
    <idioma>PT</idioma>
</dados-pedido>
XML
            ],
            [
                new Order(
                    '1234',
                    10000,
                    Currency::EUR,
                    'descrição',
                    CieloLanguage::ENGLISH,
                    10000
                ),
                <<<XML
<dados-pedido>
    <numero>1234</numero>
    <valor>10000</valor>
    <moeda>978</moeda>
    <data-hora>%s</data-hora>
    <descricao>descrição</descricao>
    <idioma>EN</idioma>
    <taxa-embarque>10000</taxa-embarque>
</dados-pedido>
XML
            ],
            [
                new Order(
                    '1234',
                    10000,
                    Currency::GBP,
                    null,
                    CieloLanguage::SPANISH,
                    null,
                    'descritor'
                ),
                <<<XML
<dados-pedido>
    <numero>1234</numero>
    <valor>10000</valor>
    <moeda>826</moeda>
    <data-hora>%s</data-hora>
    <idioma>ES</idioma>
    <soft-descriptor>descritor</soft-descriptor>
</dados-pedido>
XML
            ]
        ];
    }

    /**
     * @test
     * @param Order  $order
     * @param string $xml
     * @dataProvider provideDifferentOrders
     */
    public function withRealXmlWriter(Order $order, $xml)
    {
        if (! class_exists(\XMLWriter::class)) {
            $this->markTestSkipped();
        }

        $writer = new \XMLWriter();

        $writer->openMemory();

        $visitor = new OrderSerializationVisitor($writer);

        $visitor->visit($order);

        $this->assertXmlStringEqualsXmlString(
            sprintf(
                $xml,
                $order->getCreatedAt()->format('Y-m-d\TH:i:s')
            ),
            $writer->outputMemory(true)
        );
    }
}
