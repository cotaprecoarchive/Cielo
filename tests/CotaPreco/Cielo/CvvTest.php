<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class CvvTest extends TestCase
{
    /**
     * @test
     */
    public function fromString()
    {
        $this->assertEquals('1234', (string) Cvv::fromString('1234'));
    }
}
