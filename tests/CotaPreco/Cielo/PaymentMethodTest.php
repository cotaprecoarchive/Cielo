<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class PaymentMethodTest extends TestCase
{
    /**
     * @var PaymentMethod
     */
    private $paymentMethod;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->paymentMethod = PaymentMethod::forIssuerAsDebitPayment(
            CardIssuer::fromIssuerString(CardIssuer::VISA)
        );
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
    public function isDebit()
    {
        $this->assertTrue($this->paymentMethod->isDebit());
    }

    /**
     * @test
     */
    public function isOneTimePayment()
    {
        $this->assertFalse($this->paymentMethod->isOneTimePayment());
    }

    /**
     * @test
     */
    public function isWithInstallmentsByMerchant()
    {
        $this->assertFalse($this->paymentMethod->isWithInstallmentsByMerchant());
    }

    /**
     * @test
     */
    public function isWithInstallmentsByCardIssuers()
    {
        $this->assertFalse($this->paymentMethod->isWithInstallmentsByCardIssuers());
    }

    /**
     * @test
     */
    public function forIssuerAsOneTimePayment()
    {
        $paymentMethod = PaymentMethod::forIssuerAsOneTimePayment(
            $this->paymentMethod->getCardIssuer()
        );

        $this->assertSame(PaymentProduct::ONE_TIME_PAYMENT, $paymentMethod->getProduct());
    }

    /**
     * @test
     */
    public function forIssuerWithInstallmentsByMerchant()
    {
        $paymentMethod = PaymentMethod::forIssuerWithInstallmentsByMerchant(
            $this->paymentMethod->getCardIssuer(),
            8
        );

        $this->assertSame(
            PaymentProduct::INSTALLMENTS_BY_AFFILIATED_MERCHANTS,
            $paymentMethod->getProduct()
        );

        $this->assertEquals('08', (string) $paymentMethod->getInstallments());
    }

    /**
     * @test
     */
    public function forIssuerWithInstallmentsByCardIssuers()
    {
        $paymentMethod = PaymentMethod::forIssuerWithInstallmentsByCardIssuers(
            $this->paymentMethod->getCardIssuer(),
            12
        );

        $this->assertSame(
            PaymentProduct::INSTALLMENTS_BY_CARD_ISSUERS,
            $paymentMethod->getProduct()
        );

        $this->assertEquals('12', (string) $paymentMethod->getInstallments());
    }
}
