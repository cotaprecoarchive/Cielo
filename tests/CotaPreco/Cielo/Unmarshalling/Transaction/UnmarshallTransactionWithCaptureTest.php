<?php

namespace CotaPreco\Cielo\Unmarshalling\Transaction;

use CotaPreco\Cielo\Transaction;
use CotaPreco\Cielo\TransactionStatus;
use CotaPreco\CieloTestAssets\TransactionWithCapture;
use PHPUnit_Framework_TestCase as TestCase;

class UnmarshallTransactionWithCaptureTest extends TestCase
{
    /**
     * @test
     */
    public function withCapture()
    {
        $unmarshaller = new TransactionUnmarshaller();

        /* @var Transaction $transaction */
        $transaction = $unmarshaller((string) new TransactionWithCapture());

        $this->assertTrue($transaction->hasCapture());
        $this->assertSame(TransactionStatus::CAPTURED, $transaction->getStatus());
    }
}
