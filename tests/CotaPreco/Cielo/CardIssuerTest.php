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
     * @return array
     */
    public function provideCardNumbersAndExpectedIssuer()
    {
        return [
            [
                '4012001037141112',
                CardIssuer::VISA
            ],
            [
                '4012001038443335',
                CardIssuer::VISA
            ],
            [
                '5453010000066167',
                CardIssuer::MASTERCARD
            ],
            [
                '5453010000066167',
                CardIssuer::MASTERCARD
            ]
        ];
    }

    /**
     * @test
     * @param string $number
     * @param string $issuer
     * @dataProvider provideCardNumbersAndExpectedIssuer
     */
    public function fromCardNumber($number, $issuer)
    {
        $this->assertEquals(
            $issuer,
            (string) CardIssuer::fromCardNumber($number)
        );
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
