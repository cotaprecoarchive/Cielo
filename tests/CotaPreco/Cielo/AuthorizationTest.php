<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class AuthorizationTest extends TestCase
{
    /**
     * @var Authorization
     */
    private $authorization;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->authorization = new Authorization(
            5,
            'Autorização negada',
            new \DateTimeImmutable('now'),
            1000,
            '57',
            '221766'
        );
    }

    /**
     * @test
     */
    public function getCode()
    {
        $this->assertEquals(5, $this->authorization->getCode());
    }

    /**
     * @test
     */
    public function getMessage()
    {
        $this->assertEquals('Autorização negada', $this->authorization->getMessage());
    }

    /**
     * @test
     */
    public function getOcurredAt()
    {
        $this->assertInstanceOf(\DateTimeImmutable::class, $this->authorization->getOcurredAt());
    }

    /**
     * @test
     */
    public function getValue()
    {
        $this->assertEquals(1000, $this->authorization->getValue());
    }

    /**
     * @test
     */
    public function getLr()
    {
        $this->assertEquals('57', $this->authorization->getLr());
    }

    /**
     * @test
     */
    public function getNsu()
    {
        $this->assertEquals('221766', $this->authorization->getNsu());
    }
}
