<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class CreditCardExpirationTest extends TestCase
{
    /**
     * @var CreditCardExpiration
     */
    private $expiration;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->expiration = CreditCardExpiration::fromYearAndMonth(2019, 2);
    }

    /**
     * @test
     */
    public function getYear()
    {
        $this->assertEquals(2019, $this->expiration->getYear());
    }

    /**
     * @test
     */
    public function getMonth()
    {
        $this->assertEquals(2, $this->expiration->getMonth());
    }

    /**
     * @test
     */
    public function getFullYearAndMonth()
    {
        $this->assertEquals('201902', $this->expiration->getFullYearAndMonth());
    }
}
