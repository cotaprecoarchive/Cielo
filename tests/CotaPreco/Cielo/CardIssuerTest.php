<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class CardIssuerTest extends TestCase
{
    /**
     * @test
     */
    public function fromCreditCardTypeThrowsInvalidArgument()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        CardIssuer::fromCreditCardType(null);
    }

    /**
     * @test
     */
    public function toString()
    {
        $issuer = CardIssuer::fromCreditCardType(CreditCardType::VISA);

        $this->assertInstanceOf(CardIssuer::class, $issuer);
        $this->assertEquals(CreditCardType::VISA, (string) $issuer);
    }
}
