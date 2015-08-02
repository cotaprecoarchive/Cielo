<?php

namespace CotaPreco\Cielo\Request\Capture;

use CotaPreco\Cielo\Merchant;
use CotaPreco\Cielo\TransactionId;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class PartialCaptureTest extends TestCase
{
    /**
     * @var PartialCapture
     */
    private $request;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->request = new PartialCapture(
            TransactionId::fromString('1001734898073E931001'),
            Merchant::fromAffiliationIdAndKey(
                getenv('CIELO_AFFILIATION_ID'),
                getenv('CIELO_AFFILIATION_KEY')
            ),
            25000
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

    /**
     * @test
     */
    public function getValue()
    {
        $this->assertSame(25000, $this->request->getValue());
    }

    /**
     * @test
     */
    public function hasShipping()
    {
        $this->assertFalse($this->request->hasShipping());
    }

    /**
     * @test
     */
    public function getShipping()
    {
        $this->assertNull($this->request->getShipping());
    }
}
