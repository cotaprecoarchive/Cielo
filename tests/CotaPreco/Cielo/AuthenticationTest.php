<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class AuthenticationTest extends TestCase
{
    /**
     * @var Authentication
     */
    private $authentication;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->authentication = new Authentication(
            2,
            'Autenticada com sucesso',
            new \DateTimeImmutable('now'),
            1000,
            Eci::fromIndicator(5)
        );
    }

    /**
     * @test
     */
    public function getCode()
    {
        $this->assertEquals(2, $this->authentication->getCode());
    }

    /**
     * @test
     */
    public function getMessage()
    {
        $this->assertEquals('Autenticada com sucesso', $this->authentication->getMessage());
    }

    /**
     * @test
     */
    public function getOcurredAt()
    {
        $this->assertInstanceOf(\DateTimeImmutable::class, $this->authentication->getOcurredAt());
    }

    /**
     * @test
     */
    public function getValue()
    {
        $this->assertEquals(1000, $this->authentication->getValue());
    }

    /**
     * @test
     */
    public function getEci()
    {
        $this->assertInstanceOf(Eci::class, $this->authentication->getEci());
    }

    /**
     * @test
     */
    public function isAuthenticated()
    {
        $this->assertTrue($this->authentication->isAuthenticated());
    }

    /**
     * @test
     */
    public function isUnauthenticated()
    {
        $this->assertFalse($this->authentication->isUnauthenticated());
    }

    /**
     * @test
     */
    public function isWithoutAuthentication()
    {
        $this->assertFalse($this->authentication->isWithoutAuthentication());
    }
}
