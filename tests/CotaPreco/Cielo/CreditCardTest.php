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
        $number = '4012001037141112';

        return [
            [$number, 2018, 5, CardSecurityCodeIndicator::WITHOUT_SECURITY_CODE],
            [$number, 2018, 5, CardSecurityCodeIndicator::INEXISTENT_SECURITY_CODE],
            [$number, 2018, 5, CardSecurityCodeIndicator::SECURITY_CODE_UNREADABLE],
            [$number, 2018, 5, CardSecurityCodeIndicator::WITH_SECURITY_CODE, Cvv::fromVerificationValue('123')]
        ];
    }

    /**
     * @test
     * @param string   $number
     * @param int      $year
     * @param int      $month
     * @param int      $indicator
     * @param null|Cvv $cvv
     * @dataProvider provideCardDetails
     */
    public function createFromProvidedDetails($number, $year, $month, $indicator, Cvv $cvv = null)
    {
        switch ($indicator) {
            case CardSecurityCodeIndicator::WITHOUT_SECURITY_CODE:
                $card = CreditCard::createWithoutSecurityCode($number, $year, $month);
                break;

            case CardSecurityCodeIndicator::SECURITY_CODE_UNREADABLE:
                $card = CreditCard::createWithUnreadableSecurityCode($number, $year, $month);
                break;

            case CardSecurityCodeIndicator::INEXISTENT_SECURITY_CODE:
                $card = CreditCard::createWithInexistentSecurityCode($number, $year, $month);
                break;

            case CardSecurityCodeIndicator::WITH_SECURITY_CODE:
            default:
                $card = CreditCard::createWithSecurityCode($number, $year, $month, $cvv);
                break;
        }

        $this->assertEquals($number, $card->getNumber());

        $this->assertInstanceOf(CreditCardExpiration::class, $card->getExpiration());

        $this->assertSame($indicator, $card->getSecurityCodeIndicator());

        $this->assertInstanceOf(Bin::class, $card->getBin());

        $this->assertTrue(
            is_null($card->getSecurityCode()) || $card->getSecurityCode() instanceof Cvv
        );
    }
}
