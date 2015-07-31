<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class SearchTransactionRequestTest extends TestCase
{
    /**
     * @var SearchTransactionRequest
     */
    private $request;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->request = new SearchTransactionRequest(
            Merchant::fromAffiliationIdAndKey(
                '1006993069',
                '25fbb99741c739dd84d7b06ec78c9bac718838630f30b112d033ce2e621b34f3'
            ),
            TransactionId::fromString('1006993069371CFF1001')
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
        $this->assertEquals('1006993069371CFF1001', (string) $this->request->getTransactionId());
    }
}
