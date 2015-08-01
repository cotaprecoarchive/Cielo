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
    public function fromIssuerStringThrowsInvalidArgument()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        CardIssuer::fromIssuerString(null);
    }

    /**
     * @test
     */
    public function toString()
    {
        $issuer = CardIssuer::fromIssuerString(CardIssuer::VISA);

        $this->assertInstanceOf(CardIssuer::class, $issuer);
        $this->assertEquals(CardIssuer::VISA, (string) $issuer);
    }
}
