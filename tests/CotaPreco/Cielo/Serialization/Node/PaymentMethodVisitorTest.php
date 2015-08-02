<?php

namespace CotaPreco\Cielo\Serialization\Node;

use CotaPreco\Cielo\CardIssuer;
use CotaPreco\Cielo\PaymentMethod;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class PaymentMethodVisitorTest extends TestCase
{
    /**
     * @return array
     */
    public function providePaymentMethodAndStructure()
    {
        return [
            [
                PaymentMethod::forIssuerAsDebitPayment(
                    CardIssuer::fromIssuerString(CardIssuer::VISA)
                ),
                <<<XML
<forma-pagamento>
    <bandeira>visa</bandeira>
    <produto>A</produto>
    <parcelas>1</parcelas>
</forma-pagamento>
XML
            ],
            [
                PaymentMethod::forIssuerAsOneTimePayment(
                    CardIssuer::fromIssuerString(CardIssuer::MASTERCARD)
                ),
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
                    CardIssuer::fromIssuerString(CardIssuer::MASTERCARD),
                    9
                ),
                <<<XML
<forma-pagamento>
    <bandeira>mastercard</bandeira>
    <produto>2</produto>
    <parcelas>09</parcelas>
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
     * @dataProvider providePaymentMethodAndStructure
     */
    public function visit($paymentMethod, $xml)
    {
        $document = new \DOMDocument();

        $root = $document->createElement('root');

        $paymentMethod->accept(new PaymentMethodVisitor($root));

        $this->assertXmlStringEqualsXmlString(
            sprintf('<root>%s</root>', $xml),
            $document->saveXML($root)
        );
    }
}
