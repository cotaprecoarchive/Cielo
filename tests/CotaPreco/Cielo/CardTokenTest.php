<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class CardTokenTest extends TestCase
{
    /**
     * @var string
     */
    const GENERATED_TOKEN = 'TuS6LeBHWjqFFtE7S3zR052Jl/KUlD+tYJFpAdlA87E=';

    /**
     * @test
     */
    public function fromPreviouslyIssuedTokenString()
    {
        $this->assertEquals(
            self::GENERATED_TOKEN,
            CardToken::fromPreviouslyIssuedTokenString(self::GENERATED_TOKEN)
        );
    }
}
