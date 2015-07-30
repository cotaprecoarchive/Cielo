<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class AffiliationIdKeyPairTest extends TestCase
{
    /**
     * @var AffiliationIdKeyPair
     */
    private $affiliation;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->affiliation = AffiliationIdKeyPair::createFromAffiliationIdAndKey(
            '1006993069',
            '25fbb997438630f30b112d033ce2e621b34f3'
        );
    }

    /**
     * @test
     */
    public function getAffiliationId()
    {
        $this->assertEquals('1006993069', $this->affiliation->getAffiliationId());
    }

    /**
     * @test
     */
    public function getAffiliationKey()
    {
        $this->assertEquals('25fbb997438630f30b112d033ce2e621b34f3', $this->affiliation->getAffiliationKey());
    }

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
}
