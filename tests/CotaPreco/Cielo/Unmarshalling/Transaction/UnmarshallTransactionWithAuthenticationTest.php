<?php

namespace CotaPreco\Cielo\Unmarshalling\Transaction;

use CotaPreco\Cielo\Authentication;
use CotaPreco\Cielo\Transaction;
use CotaPreco\CieloTestAssets\TransactionWithAuthentication;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class UnmarshallTransactionWithAuthenticationTest extends TestCase
{
    /**
     * @test
     */
    public function invoke()
    {
        $unmarshaller = new TransactionUnmarshaller();

        /* @var Transaction $transaction */
        $transaction = $unmarshaller((string) new TransactionWithAuthentication());

        $this->assertTrue($transaction->hasAuthentication());
        $this->assertInstanceOf(Authentication::class, $transaction->getAuthentication());
    }
}
