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
    public function fromVerificationValue()
    {
        $this->assertEquals('1234', (string) Cvv::fromVerificationValue('1234'));
    }

    /**
     * @test
     * @param mixed $value
     * @dataProvider provideInvalidVerificationValues
     */
    public function throwsInvalidArgument($value)
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        Cvv::fromVerificationValue($value);
    }

    /**
     * @return array
     */
    public function provideInvalidVerificationValues()
    {
        return [
            [''],
            [null],
            [1],
            [12],
            [99992],
            [-1],
            [true],
            [-1202]
        ];
    }
}
