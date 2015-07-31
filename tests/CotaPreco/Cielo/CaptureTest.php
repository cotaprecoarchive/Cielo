<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class CaptureTest extends TestCase
{
    /**
     * @var Capture
     */
    private $capture;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->capture = new Capture(
            6,
            'Transacao capturada com sucesso',
            new \DateTimeImmutable('now'),
            1000
        );
    }

    /**
     * @test
     */
    public function getCode()
    {
        $this->assertEquals(6, $this->capture->getCode());
    }

    /**
     * @test
     */
    public function getMessage()
    {
        $this->assertEquals('Transacao capturada com sucesso', $this->capture->getMessage());
    }

    /**
     * @test
     */
    public function getProcessedAt()
    {
        $this->assertInstanceOf(\DateTimeImmutable::class, $this->capture->getProcessedAt());
    }

    /**
     * @test
     */
    public function getValue()
    {
        $this->assertEquals(1000, $this->capture->getValue());
    }
}
