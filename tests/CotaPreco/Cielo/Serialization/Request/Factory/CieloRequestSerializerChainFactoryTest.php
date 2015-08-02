<?php

namespace CotaPreco\Cielo\Serialization\Request\Factory;

use CotaPreco\Cielo\Serialization\Request\SerializerChain;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class CieloRequestSerializerChainFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function invoke()
    {
        $factory = new CieloRequestSerializerChainFactory();

        $this->assertInstanceOf(SerializerChain::class, $factory());
    }
}
