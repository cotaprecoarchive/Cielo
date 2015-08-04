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

    /**
     * @test
     * @param mixed $transactionId
     * @dataProvider invalidTransactionIds
     */
    public function throwsInvalidArgumentException($transactionId)
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        TransactionId::fromString($transactionId);
    }

    /**
     * @return array
     */
    public function invalidTransactionIds()
    {
        return [
            [''],
            [null],
            ['1234'],
            [str_repeat('1', 50)],
            [false],
            [-1]
        ];
    }
}
