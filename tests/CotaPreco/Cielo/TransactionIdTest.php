<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class TransactionIdTest extends TestCase
{
    /**
     * @test
     */
    public function fromSring()
    {
        $transactonId = TransactionId::fromString('10017348980735271001');

        $this->assertEquals('10017348980735271001', (string) $transactonId);
    }
}
