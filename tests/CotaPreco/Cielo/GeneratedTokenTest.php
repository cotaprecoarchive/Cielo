<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class GeneratedTokenTest extends TestCase
{
    /**
     * @var GeneratedToken
     */
    private $generatedToken;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->generatedToken = new GeneratedToken(
            CardToken::fromString('TuS6LeBHWjqFFtE7S3zR052Jl/KUlD+tYJFpAdlA87E='),
            GeneratedTokenStatus::UNBLOCKED,
            '455187******0183'
        );
    }

    /**
     * @test
     */
    public function getToken()
    {
        $this->assertInstanceOf(CardToken::class, $this->generatedToken->getToken());
    }

    public function testGetStatus()
    {
        $this->assertSame(GeneratedTokenStatus::UNBLOCKED, $this->generatedToken->getStatus());
    }

    /**
     * @test
     */
    public function getTruncatedCardNumber()
    {
        $this->assertEquals('455187******0183', $this->generatedToken->getTruncatedCardNumber());
    }

    /**
     * @test
     */
    public function isBlocked()
    {
        $this->assertFalse($this->generatedToken->isBlocked());
    }

    /**
     * @test
     */
    public function isUnblocked()
    {
        $this->assertTrue($this->generatedToken->isUnblocked());
    }
}
