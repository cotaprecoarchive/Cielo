<?php

namespace CotaPreco\Cielo\Request;

use CotaPreco\Cielo\Merchant;
use CotaPreco\Cielo\TransactionId;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class AuthorizeTransactionTest extends TestCase
{
    /**
     * @var AuthorizeTransaction
     */
    private $request;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->request = new AuthorizeTransaction(
            TransactionId::fromString('1001734898073E931001'),
            Merchant::fromAffiliationIdAndKey(
                getenv('CIELO_AFFILIATION_ID'),
                getenv('CIELO_AFFILIATION_KEY')
            )
        );
    }

    /**
     * @test
     */
    public function getTransactionId()
    {
        $this->assertInstanceOf(TransactionId::class, $this->request->getTransactionId());
    }

    /**
     * @test
     */
    public function getMerchant()
    {
        $this->assertInstanceOf(Merchant::class, $this->request->getMerchant());
    }
}
