<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class MerchantTest extends TestCase
{
    /**
     * @var Merchant
     */
    private $merchant;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->merchant = Merchant::fromAffiliationIdAndKey('1006993069', '25fbb997438630f30b112d033ce2e621b34f3');
    }

    /**
     * @test
     */
    public function getAffiliationId()
    {
        $this->assertEquals('1006993069', $this->merchant->getAffiliationId());
    }

    /**
     * @test
     */
    public function getAffiliationKey()
    {
        $this->assertEquals('25fbb997438630f30b112d033ce2e621b34f3', $this->merchant->getAffiliationKey());
    }
}
