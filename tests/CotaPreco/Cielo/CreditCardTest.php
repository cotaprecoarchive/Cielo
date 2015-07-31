<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class CreditCardTest extends TestCase
{
    /**
     * @return array
     */
    public function provideCardDetails()
    {
        $number     = '4012001037141112';
        $expiration = CreditCardExpiration::fromYearAndMonth(2018, 5);

        return [
            [$number, $expiration, CardSecurityCodeIndicator::WITHOUT_SECURITY_CODE],
            [$number, $expiration, CardSecurityCodeIndicator::INEXISTENT_SECURITY_CODE],
            [$number, $expiration, CardSecurityCodeIndicator::SECURITY_CODE_UNREADABLE],
            [$number, $expiration, CardSecurityCodeIndicator::WITH_SECURITY_CODE, Cvv::fromString('123')]
        ];
    }

    /**
     * @test
     * @param string                $number
     * @param CreditCardExpiration  $expiration
     * @param int                   $indicator
     * @param null|CardSecurityCode $securityCode
     * @dataProvider provideCardDetails
     */
    public function createFromProvidedDetails(
        $number,
        CreditCardExpiration $expiration,
        $indicator,
        CardSecurityCode $securityCode = null
    ) {
        switch ($indicator) {
            case CardSecurityCodeIndicator::WITHOUT_SECURITY_CODE:
                $card = CreditCard::createWithoutSecurityCode($number, $expiration);
                break;

            case CardSecurityCodeIndicator::SECURITY_CODE_UNREADABLE:
                $card = CreditCard::createWithUnreadableSecurityCode($number, $expiration);
                break;

            case CardSecurityCodeIndicator::INEXISTENT_SECURITY_CODE:
                $card = CreditCard::createWithInexistentSecurityCode($number, $expiration);
                break;

            case CardSecurityCodeIndicator::WITH_SECURITY_CODE:
            default:
                $card = CreditCard::createWithSecurityCode($number, $expiration, $securityCode);
                break;
        }

        $this->assertEquals($number, $card->getNumber());
        $this->assertInstanceOf(CreditCardExpiration::class, $card->getExpiration());
        $this->assertSame($indicator, $card->getSecurityCodeIndicator());

        $this->assertTrue(
            is_null($card->getSecurityCode()) || $card->getSecurityCode() instanceof CardSecurityCode
        );
    }
}
