<?php

namespace CotaPreco\Cielo\Request\Cancellation;

use CotaPreco\Cielo\Merchant;
use CotaPreco\Cielo\TransactionId;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class PartialCancellationTest extends TestCase
{
    /**
     * @var PartialCancellation
     */
    private $request;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $merchant = Merchant::fromAffiliationIdAndKey(
            getenv('CIELO_AFFILIATION_ID'),
            getenv('CIELO_AFFILIATION_KEY')
        );

        $this->request = new PartialCancellation(
            TransactionId::fromString('12345678910111213141'),
            $merchant,
            1000
        );
    }

    /**
     * @test
     */
    public function getMerchant()
    {
        $this->assertInstanceOf(Merchant::class, $this->request->getMerchant());
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
    public function getValue()
    {
        $this->assertEquals(1000, $this->request->getValue());
    }
}
