<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class CardTokenTest extends TestCase
{
    /**
     * @test
     */
    public function fromString()
    {
        $this->assertEquals(
            'TuS6LeBHWjqFFtE7S3zR052Jl/KUlD+tYJFpAdlA87E=',
            CardToken::fromString('TuS6LeBHWjqFFtE7S3zR052Jl/KUlD+tYJFpAdlA87E=')
        );
    }
}
