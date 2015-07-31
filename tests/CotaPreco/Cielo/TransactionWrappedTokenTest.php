<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class TransactionWrappedTokenTest extends TestCase
{
    /**
     * @var TransactionWrappedToken
     */
    private $wrappedToken;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->wrappedToken = new TransactionWrappedToken(
            CardToken::fromPreviouslyIssuedTokenString('TuS6LeBHWjqFFtE7S3zR052Jl/KUlD+tYJFpAdlA87E='),
            GeneratedTokenStatus::UNBLOCKED,
            '455187******0183'
        );
    }

    /**
     * @test
     */
    public function getToken()
    {
        $this->assertInstanceOf(CardToken::class, $this->wrappedToken->getToken());
    }

    public function testGetStatus()
    {
        $this->assertSame(GeneratedTokenStatus::UNBLOCKED, $this->wrappedToken->getStatus());
    }

    /**
     * @test
     */
    public function getTruncatedCardNumber()
    {
        $this->assertEquals('455187******0183', $this->wrappedToken->getTruncatedCardNumber());
    }

    /**
     * @test
     */
    public function isBlocked()
    {
        $this->assertFalse($this->wrappedToken->isBlocked());
    }

    /**
     * @test
     */
    public function isUnblocked()
    {
        $this->assertTrue($this->wrappedToken->isUnblocked());
    }
}
