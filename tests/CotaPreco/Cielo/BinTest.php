<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class BinTest extends TestCase
{
    /**
     * @test
     */
    public function fromString()
    {
        $this->assertEquals('455187', (string) Bin::fromString('455187'));
    }
}
