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

    /**
     * @test
     * @param mixed $binString
     * @dataProvider provideInvalidBins
     */
    public function throwsInvalidArgument($binString)
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        Bin::fromString($binString);
    }

    /**
     * @return array
     */
    public function provideInvalidBins()
    {
        return [
            [''],
            [null],
            [false],
            ['1234567'],
            ['####']
        ];
    }
}
