<?php

namespace CotaPreco\Cielo\Serialization\Request;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class DefaultCieloRequestSerializerTest extends TestCase
{
    /**
     * @test
     */
    public function construct()
    {
        $serializer = new DefaultCieloRequestSerializer();

        $this->assertAttributeContainsOnly(
            SerializerInterface::class,
            'serializers',
            $serializer
        );
    }
}
