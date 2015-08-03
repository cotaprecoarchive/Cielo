<?php

namespace CotaPreco\Cielo\Unmarshalling\Transaction;

use CotaPreco\Cielo\GeneratedToken;
use CotaPreco\Cielo\Transaction;
use CotaPreco\CieloTestAssets\TransactionWithGeneratedToken;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class UnmarshallTransactionWithGeneratedTokenTest extends TestCase
{
    /**
     * @test
     */
    public function invoke()
    {
        $unmarshaller = new TransactionUnmarshaller();

        /* @var Transaction $transaction */
        $transaction = $unmarshaller((string) new TransactionWithGeneratedToken());

        $this->assertTrue($transaction->hasGeneratedToken());

        $this->assertInstanceOf(
            GeneratedToken::class,
            $transaction->getGeneratedToken()
        );
    }
}
