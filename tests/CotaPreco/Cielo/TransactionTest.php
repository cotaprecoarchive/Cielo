<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 */
class TransactionTest extends TestCase
{
    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->transaction = new Transaction(
            TransactionId::fromString('10017348980735271001'),
            Pan::fromTokenString('IqVz7P9zaIgTYdU41HaW/OB/d7Idwttqwb2vaTt8MT0='),
            Order::fromPreviouslyIssuedOrder(
                '1196683550',
                '1000',
                new \DateTimeImmutable('2011-12-08T10:44:24.244-02:00')
            ),
            PaymentMethod::forIssuerAsOneTimePayment(
                CardIssuer::fromIssuerString(CardIssuer::VISA)
            ),
            TransactionStatus::CREATED
        );
    }

    /**
     * @test
     */
    public function getTransactionId()
    {
        $this->assertInstanceOf(TransactionId::class, $this->transaction->getTransactionId());
    }

    /**
     * @test
     */
    public function getPan()
    {
        $this->assertInstanceOf(Pan::class, $this->transaction->getPan());
    }

    /**
     * @test
     */
    public function getOrder()
    {
        $this->assertInstanceOf(Order::class, $this->transaction->getOrder());
    }

    /**
     * @test
     */
    public function getPaymentMethod()
    {
        $this->assertInstanceOf(PaymentMethod::class, $this->transaction->getPaymentMethod());
    }

    public function testGetStatus()
    {
        $this->assertSame(TransactionStatus::CREATED, $this->transaction->getStatus());
    }

    /**
     * @test
     */
    public function hasAuthentication()
    {
        $this->assertFalse($this->transaction->hasAuthentication());
    }

    /**
     * @test
     */
    public function getAuthentication()
    {
        $this->assertNull($this->transaction->getAuthentication());
    }

    /**
     * @test
     */
    public function authenticate()
    {
        $this->transaction->authenticate(
            new Authentication(
                2,
                'Autenticada com sucesso',
                new \DateTimeImmutable('2011-12-08T10:44:47.311-02:00'),
                1000,
                Eci::fromIndicator(5)
            )
        );

        $this->assertTrue($this->transaction->hasAuthentication());
        $this->assertInstanceOf(Authentication::class, $this->transaction->getAuthentication());
    }

    /**
     * @test
     */
    public function hasAuthorization()
    {
        $this->assertFalse($this->transaction->hasAuthorization());
    }

    /**
     * @test
     */
    public function getAuthorization()
    {
        $this->assertNull($this->transaction->getAuthorization());
    }

    /**
     * @test
     */
    public function authorize()
    {
        $this->transaction->authorize(
            new Authorization(
                5,
                'Autorização negada',
                new \DateTimeImmutable('2011-12-09T10:58:45.847-02:00'),
                1000,
                '57',
                '221766'
            )
        );

        $this->assertTrue($this->transaction->hasAuthorization());
        $this->assertInstanceOf(Authorization::class, $this->transaction->getAuthorization());
    }

    /**
     * @test
     */
    public function hasGeneratedToken()
    {
        $this->assertFalse($this->transaction->hasGeneratedToken());
    }

    /**
     * @test
     */
    public function getGeneratedToken()
    {
        $this->assertNull($this->transaction->getGeneratedToken());
    }

    /**
     * @test
     */
    public function withGeneratedToken()
    {
        $transaction = new Transaction(
            $this->transaction->getTransactionId(),
            $this->transaction->getPan(),
            $this->transaction->getOrder(),
            $this->transaction->getPaymentMethod(),
            $this->transaction->getStatus(),
            new GeneratedToken(
                CardToken::fromString('TuS6LeBHWjqFFtE7S3zR052Jl/KUlD+tYJFpAdlA87E='),
                GeneratedTokenStatus::UNBLOCKED,
                '455187******0183'
            )
        );

        $this->assertTrue($transaction->hasGeneratedToken());
        $this->assertInstanceOf(GeneratedToken::class, $transaction->getGeneratedToken());
    }

    /**
     * @test
     */
    public function hasCapture()
    {
        $this->assertFalse($this->transaction->hasCapture());
    }

    /**
     * @test
     */
    public function getCapture()
    {
        $this->assertNull($this->transaction->getCapture());
    }

    /**
     * @test
     */
    public function capture()
    {
        $this->transaction->capture(
            new Capture(
                6,
                'Transacao capturada com sucesso',
                new \DateTimeImmutable('2011-12-08T14:23:08.779-02:00'),
                900,
                900
            )
        );

        $this->assertTrue($this->transaction->hasCapture());
        $this->assertInstanceOf(Capture::class, $this->transaction->getCapture());
    }

    /**
     * @test
     */
    public function getAuthenticationUrl()
    {
        $this->assertNull($this->transaction->getAuthenticationUrl());
    }

    /**
     * @test
     */
    public function withAuthenticationUrl()
    {
        $this->transaction->withAuthenticationUrl(Url::fromString('http://localhost/cielo'));

        $this->assertEquals(
            'http://localhost/cielo',
            $this->transaction->getAuthenticationUrl()
        );
    }

    /**
     * @test
     */
    public function getCancellations()
    {
        $this->assertCount(0, $this->transaction->getCancellations());
    }

    /**
     * @test
     */
    public function hasCancellations()
    {
        $this->assertFalse($this->transaction->hasCancellations());
    }

    /**
     * @test
     */
    public function cancel()
    {
        for ($i = 0; $i < 5; $i += 1) {
            $this->transaction->cancel(
                new Cancellation(
                    9,
                    'Transacao cancelada com sucesso',
                    new \DateTimeImmutable('2011-12-08T16:46:35.109-02:00'),
                    1000
                )
            );
        }

        $this->assertCount(5, $this->transaction->getCancellations());
        $this->assertTrue($this->transaction->hasCancellations());
    }
}
