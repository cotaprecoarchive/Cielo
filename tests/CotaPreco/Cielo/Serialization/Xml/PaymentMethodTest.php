<?php

namespace CotaPreco\Cielo\Serialization\Xml;

use CotaPreco\Cielo\CardIssuer;
use CotaPreco\Cielo\PaymentMethod;
use CotaPreco\Cielo\PaymentProduct;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class PaymentMethodTest extends TestCase
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
            ->with($this->equalTo('forma-pagamento'));

        $writer->expects($this->exactly(3))
            ->method('writeElement')
            ->withConsecutive(
                [
                    $this->equalTo('bandeira'),
                    CardIssuer::VISA
                ],
                [
                    $this->equalTo('produto'),
                    PaymentProduct::ONE_TIME_PAYMENT
                ],
                [
                    $this->equalTo('parcelas'),
                    1
                ]
            );

        $writer->expects($this->once())
            ->method('endElement');

        $visitor = new PaymentMethodSerializationVisitor($writer);

        $visitor->visit(PaymentMethod::forIssuerAsOneTimePayment(
            CardIssuer::fromIssuerString(CardIssuer::VISA)
        ));
    }

    /**
     * @return array
     */
    public function providePaymentMethods()
    {
        return [
            [
                PaymentMethod::forIssuerAsDebitPayment(CardIssuer::fromIssuerString(CardIssuer::VISA)),
                <<<XML
<forma-pagamento>
    <bandeira>visa</bandeira>
    <produto>A</produto>
    <parcelas>1</parcelas>
</forma-pagamento>
XML
            ],
            [
                PaymentMethod::forIssuerAsOneTimePayment(CardIssuer::fromIssuerString(CardIssuer::MASTERCARD)),
                <<<XML
<forma-pagamento>
    <bandeira>mastercard</bandeira>
    <produto>1</produto>
    <parcelas>1</parcelas>
</forma-pagamento>
XML
            ],
            [
                PaymentMethod::forIssuerWithInstallmentsByMerchant(
                    CardIssuer::fromIssuerString(CardIssuer::VISA),
                    7
                ),
                <<<XML
<forma-pagamento>
    <bandeira>visa</bandeira>
    <produto>2</produto>
    <parcelas>07</parcelas>
</forma-pagamento>
XML
            ],
            [
                PaymentMethod::forIssuerWithInstallmentsByCardIssuers(
                    CardIssuer::fromIssuerString(CardIssuer::VISA),
                    12
                ),
                <<<XML
<forma-pagamento>
    <bandeira>visa</bandeira>
    <produto>3</produto>
    <parcelas>12</parcelas>
</forma-pagamento>
XML
            ]
        ];
    }

    /**
     * @test
     * @param PaymentMethod $paymentMethod
     * @param string        $xml
     * @dataProvider providePaymentMethods
     */
    public function withRealXmlWriter(PaymentMethod $paymentMethod, $xml)
    {
        if (! class_exists(\XMLWriter::class)) {
            $this->markTestSkipped();
        }

        $writer = new \XMLWriter();

        $writer->openMemory();

        $visitor = new PaymentMethodSerializationVisitor($writer);

        $visitor->visit($paymentMethod);

        $this->assertXmlStringEqualsXmlString(
            $xml,
            $writer->outputMemory(true)
        );
    }
}
