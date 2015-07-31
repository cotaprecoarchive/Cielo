<?php

namespace CotaPreco\Cielo\Unmarshalling\SimpleXml;

use CotaPreco\Cielo\PaymentMethod;
use CotaPreco\Cielo\PaymentProduct;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class PaymentMethodFromRootNodeTest extends TestCase
{
    /**
     * @return array
     */
    public function provideProductAndInstallments()
    {
        return [
            [PaymentProduct::DEBIT, 1],
            [PaymentProduct::ONE_TIME_PAYMENT, 1],
            [PaymentProduct::INSTALLMENTS_BY_CARD_ISSUERS, 3],
            [PaymentProduct::INSTALLMENTS_BY_AFFILIATED_MERCHANTS, 9]
        ];
    }

    /**
     * @test
     * @param string|int $product
     * @param int        $installments,
     * @dataProvider provideProductAndInstallments
     */
    public function invoke($product, $installments)
    {
        $createStructure = function ($product, $installments) {
            $xml = <<<PAYMENT_METHOD
<forma-pagamento>
    <bandeira>visa</bandeira>
    <produto>%s</produto>
    <parcelas>%d</parcelas>
</forma-pagamento>
PAYMENT_METHOD;

            return sprintf($xml, $product, $installments);
        };

        $paymentMethod = new PaymentMethodFromRootNode();

        $paymentMethod = $paymentMethod(new \SimpleXMLElement(
            $createStructure($product, $installments)
        ));

        /* @var PaymentMethod $paymentMethod */
        $this->assertEquals($installments, (string)  $paymentMethod->getInstallments());
        $this->assertEquals($product, $paymentMethod->getProduct());
    }
}
