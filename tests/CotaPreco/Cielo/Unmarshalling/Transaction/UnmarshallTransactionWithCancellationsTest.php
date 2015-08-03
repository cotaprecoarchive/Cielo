<?php

namespace CotaPreco\Cielo\Unmarshalling\Transaction;

use CotaPreco\Cielo\Cancellation;
use CotaPreco\Cielo\Transaction;
use CotaPreco\Cielo\TransactionStatus;
use CotaPreco\CieloTestAssets\TransactionWithCancellations;
use CotaPreco\CieloTestAssets\TransactionWithMultipleCancellations;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class UnmarshallTransactionWithCancellationsTest extends TestCase
{
    /**
     * @test
     */
    public function invoke()
    {
        $unmarshaller = new TransactionUnmarshaller();

        /* @var Transaction $transaction */
        $transaction = $unmarshaller(
            (string) new TransactionWithCancellations()
        );

        $this->assertTrue($transaction->hasCancellations());
        $this->assertSame(TransactionStatus::CANCELLED, $transaction->getStatus());
        $this->assertContainsOnlyInstancesOf(Cancellation::class, $transaction->getCancellations());
    }

    /**
     * @test
     */
    public function withMultipleCancellations()
    {
        $unmarshaller = new TransactionUnmarshaller();

        /* @var Transaction $transaction */
        $transaction = $unmarshaller((string) new TransactionWithMultipleCancellations());

        $this->assertTrue($transaction->hasCancellations());

        $this->assertGreaterThan(
            1,
            count($transaction->getCancellations())
        );
    }
}
