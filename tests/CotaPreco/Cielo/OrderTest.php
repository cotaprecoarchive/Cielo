<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class OrderTest extends TestCase
{
    /**
     * @var Order
     */
    private $order;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->order = new Order('102030', 1000);
    }

    /**
     * @test
     */
    public function fromPreviouslyIssuedOrder()
    {
        $yesterday = new \DateTimeImmutable('yesterday');

        $order = Order::fromPreviouslyIssuedOrder('102030', 1000, $yesterday);

        $this->assertSame($yesterday, $order->getCreatedAt());
    }

    /**
     * @test
     */
    public function getNumber()
    {
        $this->assertEquals('102030', $this->order->getNumber());
    }

    /**
     * @test
     */
    public function getValue()
    {
        $this->assertEquals(1000, $this->order->getValue());
    }

    /**
     * @test
     */
    public function getCurrency()
    {
        $this->assertSame(Currency::REAL, $this->order->getCurrency());
    }

    /**
     * @test
     */
    public function getCreatedAt()
    {
        $this->assertInstanceOf(\DateTimeImmutable::class, $this->order->getCreatedAt());
    }

    /**
     * @test
     */
    public function getLanguage()
    {
        $this->assertSame(CieloLanguage::PORTUGUESE, $this->order->getLanguage());
    }

    /**
     * @test
     */
    public function getDescription()
    {
        $this->assertNull($this->order->getDescription());
    }

    /**
     * @test
     */
    public function getShipping()
    {
        $this->assertNull($this->order->getShipping());
    }

    /**
     * @test
     */
    public function getDescriptor()
    {
        $this->assertNull($this->order->getDescriptor());
    }

    /**
     * @test
     */
    public function withShipping()
    {
        $orderWithShipping = $this->order->withShipping(5000);

        $this->assertInstanceOf(Order::class, $orderWithShipping);
        $this->assertNotSame($orderWithShipping, $this->order);
        $this->assertEquals(5000, $orderWithShipping->getShipping());
    }
}
