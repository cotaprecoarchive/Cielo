<?php

namespace CotaPreco\Cielo\Serialization\Exception;

use CotaPreco\Cielo\RequestInterface;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class SerializerNotFoundExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function forRequest()
    {
        /* @var RequestInterface $request */
        $request = $this->getMock(RequestInterface::class);

        $exception = SerializerNotFoundException::forRequest($request);

        $this->assertInstanceOf(\RuntimeException::class, $exception);
    }
}
