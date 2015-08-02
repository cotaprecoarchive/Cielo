<?php

namespace CotaPreco\Cielo\Unmarshalling\Node;

use CotaPreco\Cielo\CardIssuer;
use CotaPreco\Cielo\PaymentMethod;
use CotaPreco\Cielo\PaymentProduct;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class ExtractPaymentMethodTest extends TestCase
{
    /**
     * @return array
     */
    public function providePaymentMethodDetails()
    {
        return [
            [
                PaymentProduct::DEBIT,
                CardIssuer::VISA,
                1
            ],
            [
                PaymentProduct::INSTALLMENTS_BY_AFFILIATED_MERCHANTS,
                CardIssuer::VISA,
                10
            ],
            [
                PaymentProduct::INSTALLMENTS_BY_CARD_ISSUERS,
                CardIssuer::MASTERCARD,
                5
            ],
            [
                PaymentProduct::ONE_TIME_PAYMENT,
                CardIssuer::MASTERCARD,
                1
            ]
        ];
    }

    /**
     * @test
     * @param string|int $product
     * @param string     $issuer
     * @param int        $installments
     * @dataProvider providePaymentMethodDetails
     */
    public function invoke($product, $issuer, $installments)
    {
        $extractPaymentMethod = new ExtractPaymentMethod();

        $xml = <<<FORMA_PAGAMENTO
<forma-pagamento>
    <bandeira>%s</bandeira>
    <produto>%s</produto>
    <parcelas>%s</parcelas>
</forma-pagamento>
FORMA_PAGAMENTO;

        /* @var PaymentMethod $paymentMethod */
        $paymentMethod = $extractPaymentMethod(dom_import_simplexml(
            simplexml_load_string(
                sprintf($xml, $issuer, $product, $installments)
            )
        ));

        $this->assertSame($product, $paymentMethod->getProduct());
        $this->assertEquals($issuer, $paymentMethod->getCardIssuer());
        $this->assertEquals($installments, $paymentMethod->getNumberOfInstallments());
    }

    /**
     * @test
     */
    public function returnsNull()
    {
        $extractPaymentMethod = new ExtractPaymentMethod();

        $paymentMethod = $extractPaymentMethod(dom_import_simplexml(
            simplexml_load_string(
                <<<FORMA_PAGAMENTO
<forma-pagamento>
    <bandeira>visa</bandeira>
    <produto>1234</produto>
    <parcelas>1</parcelas>
</forma-pagamento>
FORMA_PAGAMENTO
            )
        ));

        $this->assertNull($paymentMethod);
    }
}
