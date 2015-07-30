<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class InstallmentsTest extends TestCase
{
    /**
     * @return array
     */
    public function provideInstallments()
    {
        return [
            [2, '02'],
            [3, '03'],
            [4, '04'],
            [5, '05'],
            [6, '06'],
            [7, '07'],
            [8, '08'],
            [10, '10'],
            [11, '11'],
            [12, '12'],
            [24, '24']
        ];
    }

    /**
     * @test
     * @param int    $installments
     * @param string $installmentsString
     * @dataProvider provideInstallments
     */
    public function fromNumberOfInstallments($installments, $installmentsString)
    {
        $this->assertEquals(
            $installmentsString,
            (string) Installments::fromNumberOfInstallments($installments)
        );
    }

    /**
     * @test
     */
    public function fromNumberOfInstallmentsThrowsLogicException()
    {
        $this->setExpectedException(\LogicException::class);

        Installments::fromNumberOfInstallments(1);
    }
}
