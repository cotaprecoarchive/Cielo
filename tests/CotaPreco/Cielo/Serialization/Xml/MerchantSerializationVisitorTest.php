<?php

namespace CotaPreco\Cielo\Serialization\Xml;

use CotaPreco\Cielo\Merchant;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class MerchantSerializationVisitorTest extends TestCase
{
    /**
     * @var Merchant
     */
    private $merchant;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->merchant = Merchant::fromAffiliationIdAndKey(
            getenv('CIELO_AFFILIATION_ID'),
            getenv('CIELO_AFFILIATION_KEY')
        );
    }

    /**
     * @test
     */
    public function visit()
    {
        /* @var \PHPUnit_Framework_MockObject_MockObject|\XMLWriter $writer */
        $writer = $this->getMock(\XMLWriter::class, [
            'startElement',
            'writeElement',
            'endElement'
        ]);

        $writer->expects($this->once())
            ->method('startElement')
            ->with($this->equalTo('dados-ec'));

        $writer->expects($this->exactly(2))
            ->method('writeElement')
            ->withConsecutive(
                [
                    $this->equalTo('numero'),
                    $this->equalTo(getenv('CIELO_AFFILIATION_ID'))
                ],
                [
                    $this->equalTo('chave'),
                    $this->equalTo(getenv('CIELO_AFFILIATION_KEY'))
                ]
            );

        $writer->expects($this->once())
            ->method('endElement');

        $visitor = new MerchantSerializationVisitor($writer);

        $visitor->visit($this->merchant);
    }

    /**
     * @test
     */
    public function withRealXmlWriter()
    {
        if (! class_exists(\XMLWriter::class)) {
            $this->markTestSkipped();
        }

        $writer = new \XMLWriter();

        $writer->openMemory();

        $visitor = new MerchantSerializationVisitor($writer);

        $visitor->visit($this->merchant);

        $expected = <<<DADOS_EC
<dados-ec>
    <numero>%s</numero>
    <chave>%s</chave>
</dados-ec>
DADOS_EC;

        $this->assertXmlStringEqualsXmlString(
            sprintf(
                $expected,
                $this->merchant->getAffiliationId(),
                $this->merchant->getAffiliationKey()
            ),
            $writer->outputMemory(true)
        );
    }
}
