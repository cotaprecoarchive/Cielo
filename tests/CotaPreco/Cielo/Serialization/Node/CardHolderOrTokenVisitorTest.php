<?php

namespace CotaPreco\Cielo\Serialization\Node;

use CotaPreco\Cielo\CardHolder;
use CotaPreco\Cielo\CardToken;
use CotaPreco\Cielo\CreditCard;
use CotaPreco\Cielo\Cvv;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class CardHolderOrTokenVisitorTest extends TestCase
{
    /**
     * @return array
     */
    public function provideHolderAndStructure()
    {
        return [
            [
                CardToken::fromString('TuS6LeBHWjqFFtE7S3zR052Jl/KUlD+tYJFpAdlA87E='),
                <<<XML
<dados-portador>
    <token>TuS6LeBHWjqFFtE7S3zR052Jl/KUlD+tYJFpAdlA87E=</token>
</dados-portador>
XML
            ],
            [
                CardHolder::fromHolderNameAndCard(
                    'FULANO DA SILVA',
                    CreditCard::createWithoutSecurityCode('4012001038443335', 2015, 8)
                ),
                <<<XML
<dados-portador>
    <numero>4012001038443335</numero>
    <validade>201508</validade>
    <indicador>0</indicador>
    <nome-portador>FULANO DA SILVA</nome-portador>
</dados-portador>
XML
            ],
            [
                CardHolder::fromCard(
                    CreditCard::createWithInexistentSecurityCode(
                        '4012001038443335',
                        2015,
                        8
                    )
                ),
                <<<XML
<dados-portador>
    <numero>4012001038443335</numero>
    <validade>201508</validade>
    <indicador>9</indicador>
</dados-portador>
XML
            ],
            [
                CardHolder::fromCard(
                    CreditCard::createWithSecurityCode(
                        '4012001038443335',
                        2015,
                        8,
                        Cvv::fromVerificationValue('973')
                    )
                ),
                <<<XML
<dados-portador>
    <numero>4012001038443335</numero>
    <validade>201508</validade>
    <indicador>1</indicador>
    <codigo-seguranca>973</codigo-seguranca>
</dados-portador>
XML
            ]
        ];
    }

    /**
     * @test
     * @param CardHolder|CardToken $holder
     * @param string               $xml
     * @dataProvider provideHolderAndStructure
     */
    public function visit($holder, $xml)
    {
        $document = new \DOMDocument();

        $root = $document->createElement('root');

        $holder->accept(new CardHolderOrTokenVisitor($root));

        $this->assertXmlStringEqualsXmlString(
            sprintf('<root>%s</root>', $xml),
            $document->saveXML($root)
        );
    }
}
