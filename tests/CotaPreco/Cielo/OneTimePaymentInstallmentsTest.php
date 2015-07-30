<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class OneTimePaymentInstallmentsTest extends TestCase
{
    /**
     * @test
     */
    public function toString()
    {
        $this->assertEquals(1, (string) new OneTimePaymentInstallments());
    }
}
