<?php

namespace CotaPreco\Cielo\Serialization\Node;

use CotaPreco\Cielo\CieloLanguage;
use CotaPreco\Cielo\Currency;
use CotaPreco\Cielo\Order;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class OrderVisitorTest extends TestCase
{
    /**
     * @return array
     */
    public function provideOrdersAndStructure()
    {
        return [
            [
                Order::fromOrderNumberAndValue('12345', 10000),
                <<<XML
<dados-pedido>
    <numero>12345</numero>
    <valor>10000</valor>
    <moeda>986</moeda>
    <data-hora>%s</data-hora>
    <idioma>PT</idioma>
</dados-pedido>
XML
            ],
            [
                Order::fromPreviouslyIssuedOrder('12345', 10000, new \DateTimeImmutable('2015-01-01')),
                <<<XML
<dados-pedido>
    <numero>12345</numero>
    <valor>10000</valor>
    <moeda>986</moeda>
    <data-hora>2015-01-01T00:00:00</data-hora>
    <idioma>PT</idioma>
</dados-pedido>
XML
            ],
            [
                new Order(
                    '12345',
                    10000,
                    Currency::EUR,
                    'descrição',
                    CieloLanguage::ENGLISH
                ),
                <<<XML
<dados-pedido>
    <numero>12345</numero>
    <valor>10000</valor>
    <moeda>978</moeda>
    <data-hora>%s</data-hora>
    <descricao>descrição</descricao>
    <idioma>EN</idioma>
</dados-pedido>
XML
            ],
            [
                new Order('12345', 1000, Currency::GBP, 'descrição', CieloLanguage::SPANISH, 1000),
                <<<XML
<dados-pedido>
    <numero>12345</numero>
    <valor>1000</valor>
    <moeda>826</moeda>
    <data-hora>%s</data-hora>
    <descricao>descrição</descricao>
    <idioma>ES</idioma>
    <taxa-embarque>1000</taxa-embarque>
</dados-pedido>
XML
            ],
            [
                new Order('12345', 1000, Currency::USD, 'descrição', CieloLanguage::ENGLISH, 1000, 'descritor'),
                <<<XML
<dados-pedido>
    <numero>12345</numero>
    <valor>1000</valor>
    <moeda>840</moeda>
    <data-hora>%s</data-hora>
    <descricao>descrição</descricao>
    <idioma>EN</idioma>
    <soft-descriptor>descritor</soft-descriptor>
    <taxa-embarque>1000</taxa-embarque>
</dados-pedido>
XML
            ]
        ];
    }

    /**
     * @test
     * @param Order  $order
     * @param string $xml
     * @dataProvider provideOrdersAndStructure
     */
    public function visit(Order $order, $xml)
    {
        $document = new \DOMDocument();

        $root = $document->createElement('root');

        $order->accept(new OrderVisitor($root));

        $this->assertXmlStringEqualsXmlString(
            sprintf(
                '<root>%s</root>',
                sprintf($xml, $order->getCreatedAt()->format('Y-m-d\TH:i:s'))
            ),
            $document->saveXML($root)
        );
    }
}
