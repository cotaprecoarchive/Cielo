<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class PaymentMethodTest extends TestCase
{
    /**
     * @var CardIssuer
     */
    private $visa;

    /**
     * @var PaymentMethod
     */
    private $paymentMethod;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->visa          = CardIssuer::fromIssuerString(CardIssuer::VISA);
        $this->paymentMethod = PaymentMethod::forIssuerAsDebitPayment($this->visa);
    }

    /**
     * @test
     */
    public function getCardIssuer()
    {
        $this->assertInstanceOf(CardIssuer::class, $this->paymentMethod->getCardIssuer());
    }

    /**
     * @test
     */
    public function getProduct()
    {
        $this->assertSame(PaymentProduct::DEBIT, $this->paymentMethod->getProduct());
    }

    /**
     * @test
     */
    public function getInstallments()
    {
        $this->assertInstanceOf(OneTimePaymentInstallments::class, $this->paymentMethod->getInstallments());
    }

    /**
     * @test
     */
    public function getNumberOfInstallments()
    {
        $this->assertEquals(1, $this->paymentMethod->getNumberOfInstallments());
    }

    /**
     * @test
     */
    public function forIssuerAsOneTimePayment()
    {
        $paymentMethod = PaymentMethod::forIssuerAsOneTimePayment($this->visa);

        $this->assertSame(PaymentProduct::ONE_TIME_PAYMENT, $paymentMethod->getProduct());
    }

    /**
     * @test
     */
    public function forIssuerWithInstallmentsByMerchant()
    {
        $paymentMethod = PaymentMethod::forIssuerWithInstallmentsByMerchant($this->visa, 8);

        $this->assertSame(PaymentProduct::INSTALLMENTS_BY_AFFILIATED_MERCHANTS, $paymentMethod->getProduct());
        $this->assertEquals('08', (string) $paymentMethod->getInstallments());
    }

    /**
     * @test
     */
    public function forIssuerWithInstallmentsByCardIssuers()
    {
        $paymentMethod = PaymentMethod::forIssuerWithInstallmentsByCardIssuers($this->visa, 12);

        $this->assertSame(PaymentProduct::INSTALLMENTS_BY_CARD_ISSUERS, $paymentMethod->getProduct());
        $this->assertEquals('12', (string) $paymentMethod->getInstallments());
    }
}
