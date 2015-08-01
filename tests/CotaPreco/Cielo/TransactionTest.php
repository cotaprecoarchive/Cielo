<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
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
        $this->transaction = Transaction::createFromRequiredComponents(
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
    public function withAuthentication()
    {
        $transaction = $this->transaction->withAuthentication(
            new Authentication(
                2,
                'Autenticada com sucesso',
                new \DateTimeImmutable('2011-12-08T10:44:47.311-02:00'),
                1000,
                Eci::fromIndicator(5)
            )
        );

        $this->assertNotSame($transaction, $this->transaction);
        $this->assertTrue($transaction->hasAuthentication());
        $this->assertInstanceOf(Authentication::class, $transaction->getAuthentication());
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
    public function withAuthorization()
    {
        $transaction = $this->transaction->withAuthorization(
            new Authorization(
                5,
                'Autorização negada',
                new \DateTimeImmutable('2011-12-09T10:58:45.847-02:00'),
                1000,
                '57',
                '221766'
            )
        );

        $this->assertNotSame($transaction, $this->transaction);
        $this->assertTrue($transaction->hasAuthorization());
        $this->assertInstanceOf(Authorization::class, $transaction->getAuthorization());
    }

    /**
     * @test
     */
    public function hasWrappedToken()
    {
        $this->assertFalse($this->transaction->hasWrappedToken());
    }

    /**
     * @test
     */
    public function getToken()
    {
        $this->assertNull($this->transaction->getToken());
    }

    /**
     * @test
     */
    public function withWrappedToken()
    {
        $transaction = $this->transaction->withWrappedToken(new TransactionWrappedToken(
            CardToken::fromString('TuS6LeBHWjqFFtE7S3zR052Jl/KUlD+tYJFpAdlA87E='),
            GeneratedTokenStatus::UNBLOCKED,
            '455187******0183'
        ));

        $this->assertNotSame($transaction, $this->transaction);
        $this->assertTrue($transaction->hasWrappedToken());
        $this->assertInstanceOf(TransactionWrappedToken::class, $transaction->getToken());
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
    public function withCapture()
    {
        $transaction = $this->transaction->withCapture(
            new Capture(
                6,
                'Transacao capturada com sucesso',
                new \DateTimeImmutable('2011-12-08T14:23:08.779-02:00'),
                900,
                900
            )
        );

        $this->assertNotSame($transaction, $this->transaction);
        $this->assertTrue($transaction->hasCapture());
        $this->assertInstanceOf(Capture::class, $transaction->getCapture());
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
    public function isFullyCancelled()
    {
        $this->assertFalse($this->transaction->isFullyCancelled());
    }

    /**
     * @test
     */
    public function withCancellations()
    {
        $cancellations = [];

        for ($i = 0; $i < 5; $i += 1) {
            $cancellations[] = new Cancellation(
                9,
                'Transacao cancelada com sucesso',
                new \DateTimeImmutable('2011-12-08T16:46:35.109-02:00'),
                1000
            );
        }

        $transaction = $this->transaction->withCancellations(...$cancellations);

        $this->assertNotSame($transaction, $this->transaction);
        $this->assertCount(5, $transaction->getCancellations());
        $this->assertTrue($transaction->hasCancellations());
    }
}
