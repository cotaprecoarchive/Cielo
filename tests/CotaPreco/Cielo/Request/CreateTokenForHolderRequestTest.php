<?php

namespace CotaPreco\Cielo\Request;

use CotaPreco\Cielo\CardHolder;
use CotaPreco\Cielo\CreditCard;
use CotaPreco\Cielo\Merchant;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class CreateTokenForHolderRequestTest extends TestCase
{
    /**
     * @var CreateTokenForHolderRequest
     */
    private $request;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->request = new CreateTokenForHolderRequest(
            Merchant::fromAffiliationIdAndKey(
                getenv('CIELO_AFFILIATION_ID'),
                getenv('CIELO_AFFILIATION_KEY')
            ),
            CardHolder::fromCard(
                CreditCard::createWithoutSecurityCode(
                    '4012001038443335',
                    2018,
                    5
                )
            )
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
    public function getHolder()
    {
        $this->assertInstanceOf(CardHolder::class, $this->request->getHolder());
    }
}
