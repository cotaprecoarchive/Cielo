<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class AffiliationIdKeyPairTest extends TestCase
{
    /**
     * @return array
     */
    public function provideInvalidAffiliationIdAndKeys()
    {
        return [
            [null, null],
            ['', ''],
            ['1234', ''],
            ['', str_repeat('9', 101)],
            [str_repeat('9', 22), '1020304050']
        ];
    }

    /**
     * @test
     * @param mixed $affiliationId
     * @param mixed $affiliationKey
     * @dataProvider provideInvalidAffiliationIdAndKeys
     */
    public function createFromAffiliationIdAndKeyThrowsInvalidArgumentException($affiliationId, $affiliationKey)
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        AffiliationIdKeyPair::createFromAffiliationIdAndKey($affiliationId, $affiliationKey);
    }

    /**
     * @test
     */
    public function createFromAffiliationIdAndKey()
    {
        $this->assertInstanceOf(
            AffiliationIdKeyPair::class,
            AffiliationIdKeyPair::createFromAffiliationIdAndKey(
                '1020304050607080',
                uniqid('')
            )
        );
    }
}
