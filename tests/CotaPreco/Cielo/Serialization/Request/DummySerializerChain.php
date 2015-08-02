<?php

namespace CotaPreco\Cielo\Serialization\Request;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class DummySerializerChain extends SerializerChain
{
    /**
     * @param SerializerInterface ...$serializers
     */
    public function __construct(SerializerInterface ...$serializers)
    {
        parent::__construct(...$serializers);
    }
}
