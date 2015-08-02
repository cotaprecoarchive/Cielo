<?php

namespace CotaPreco\Cielo\Serialization\Xml;

use CotaPreco\Cielo\CardHolder;
use CotaPreco\Cielo\CardToken;
use CotaPreco\Cielo\CreditCard;
use CotaPreco\Cielo\Cvv;
use CotaPreco\Cielo\IdentifiesHolder;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class CardHolderSerializationVisitorTest extends TestCase
{
    /**
     * @test
     */
    public function visitCardToken()
    {
        $token = CardToken::fromString('TuS6LeBHWjqFFtE7S3zR052Jl/KUlD+tYJFpAdlA87E=');

        /* @var \PHPUnit_Framework_MockObject_MockObject|\XMLWriter $writer */
        $writer = $this->getMock(\XMLWriter::class, [
            'startElement',
            'writeElement',
            'endElement'
        ]);

        $writer->expects($this->once())
            ->method('startElement')
            ->with($this->equalTo('dados-portador'));

        $writer->expects($this->once())
            ->method('writeElement')
            ->with(
                $this->equalTo('token'),
                $this->equalTo((string) $token)
            );

        $writer->expects($this->once())
            ->method('endElement');

        $visitor = new CardHolderSerializationVisitor($writer);

        $visitor->visit($token);
    }

    /**
     * @test
     */
    public function visitCardHolderWithoutHolderName()
    {
        /* @var \PHPUnit_Framework_MockObject_MockObject|\XMLWriter $writer */
        $writer = $this->getMock(\XMLWriter::class, [
            'startElement',
            'writeElement',
            'endElement'
        ]);

        $holder = CardHolder::fromCard(
            CreditCard::createWithSecurityCode(
                '5453010000066167',
                2018,
                5,
                Cvv::fromVerificationValue('123')
            )
        );

        $withValues = [
            'numero'           => $holder->getCard()->getNumber(),
            'validade'         => $holder->getCard()->getExpiration()->getFullYearAndMonth(),
            'indicador'        => $holder->getCard()->getSecurityCodeIndicator(),
            'codigo-seguranca' => $holder->getCard()->getSecurityCode()
        ];

        $expectations = [];

        foreach ($withValues as $name => $value) {
            $expectations[] = [
                $this->equalTo($name),
                $this->equalTo($value)
            ];
        }

        $writer->expects($this->once())
            ->method('startElement')
            ->with($this->equalTo('dados-portador'));

        $writer->expects($this->exactly(count($expectations)))
            ->method('writeElement')
            ->withConsecutive(...$expectations);

        $writer->expects($this->once())
            ->method('endElement');

        $visitor = new CardHolderSerializationVisitor($writer);

        $visitor->visit($holder);
    }

    /**
     * @return array
     */
    public function provideHolders()
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
                CardHolder::fromHolderNameAndCard('John Doe', CreditCard::createWithoutSecurityCode(
                    '5453010000066167',
                    2018,
                    5
                )),
                <<<XML
<dados-portador>
    <numero>5453010000066167</numero>
    <validade>201805</validade>
    <indicador>0</indicador>
    <nome-portador>John Doe</nome-portador>
</dados-portador>
XML
            ],
            [
                CardHolder::fromCard(
                    CreditCard::createWithSecurityCode(
                        '4012001037141112',
                        2018,
                        5,
                        Cvv::fromVerificationValue('123')
                    )
                ),
                <<<XML
<dados-portador>
    <numero>4012001037141112</numero>
    <validade>201805</validade>
    <indicador>1</indicador>
    <codigo-seguranca>123</codigo-seguranca>
</dados-portador>
XML
            ]
        ];
    }

    /**
     * @test
     * @param IdentifiesHolder $holder
     * @param string           $xml
     * @dataProvider provideHolders
     */
    public function withRealXmlWriter(IdentifiesHolder $holder, $xml)
    {
        $writer = new \XMLWriter();

        $writer->openMemory();

        $visitor = new CardHolderSerializationVisitor($writer);

        $visitor->visit($holder);

        $this->assertXmlStringEqualsXmlString($xml, $writer->outputMemory(true));
    }
}
