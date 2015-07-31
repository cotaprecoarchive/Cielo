<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class CardHolderTest extends TestCase
{
    /**
     * @var CardHolder
     */
    private $holder;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->holder = CardHolder::fromHolderNameAndCard('John Doe', CreditCard::createWithoutSecurityCode(
            '5453010000066167',
            CreditCardExpiration::fromYearAndMonth(2018, 5)
        ));
    }

    /**
     * @test
     */
    public function fromCard()
    {
        $holder = CardHolder::fromCard(CreditCard::createWithoutSecurityCode(
            '5453010000066167',
            CreditCardExpiration::fromYearAndMonth(2018, 5)
        ));

        $this->assertNull($holder->getName());
    }

    /**
     * @test
     */
    public function getName()
    {
        $this->assertEquals('John Doe', $this->holder->getName());
    }

    /**
     * @test
     */
    public function getCard()
    {
        $this->assertInstanceOf(CreditCard::class, $this->holder->getCard());
    }
}
