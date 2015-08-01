<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class CancellationTest extends TestCase
{
    /**
     * @var Cancellation
     */
    private $cancellation;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cancellation = new Cancellation(
            9,
            'Transacao cancelada com sucesso',
            new \DateTimeImmutable('now'),
            1000
        );
    }

    /**
     * @test
     */
    public function getCode()
    {
        $this->assertEquals(9, $this->cancellation->getCode());
    }

    /**
     * @test
     */
    public function getMessage()
    {
        $this->assertEquals('Transacao cancelada com sucesso', $this->cancellation->getMessage());
    }

    /**
     * @test
     */
    public function getProcessedAt()
    {
        $this->assertInstanceOf(\DateTimeImmutable::class, $this->cancellation->getProcessedAt());
    }

    /**
     * @test
     */
    public function getValue()
    {
        $this->assertEquals(1000, $this->cancellation->getValue());
    }
}
