<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class EciTest extends TestCase
{
    /**
     * @return \int[][]
     */
    public function provideIndicators()
    {
        return [
            [1, SecureIndicator::WITHOUT_AUTHENTICATION],
            [2, SecureIndicator::AUTHENTICATED],
            [5, SecureIndicator::AUTHENTICATED],
            [6, SecureIndicator::WITHOUT_AUTHENTICATION],
            [7, SecureIndicator::MERCHANT_DID_NOT_SEND_AUTHENTICATION],
            [0, SecureIndicator::MERCHANT_DID_NOT_SEND_AUTHENTICATION],
            [7, SecureIndicator::UNAUTHENTICATED],
            [0, SecureIndicator::UNAUTHENTICATED],
            [-1, SecureIndicator::MERCHANT_DID_NOT_SEND_AUTHENTICATION],
            [-1, SecureIndicator::UNAUTHENTICATED]
        ];
    }

    /**
     * @test
     * @param int $indicator
     * @param int $expected
     * @dataProvider provideIndicators
     */
    public function fromIndicator($indicator, $expected)
    {
        $this->assertTrue((Eci::fromIndicator($indicator)->getIndicator() & $expected) === $expected);
    }
}
