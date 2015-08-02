<?php

namespace CotaPreco\Cielo\Serialization\Request;

use CotaPreco\Cielo\RequestInterface;
use CotaPreco\Cielo\Serialization\Exception\SerializerNotFoundException;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class SerializerChainTest extends TestCase
{
    /**
     * @test
     */
    public function invoke()
    {
        $serializerA = $this->getMock(SerializerInterface::class);

        $serializerA->expects($this->once())
            ->method('canSerialize')
            ->with(
                $this->isInstanceOf(RequestInterface::class)
            )
            ->willReturn(false);

        $serializerA->expects($this->never())
            ->method('__invoke');

        $serializerB = $this->getMock(SerializerInterface::class);

        $serializerB->expects($this->once())
            ->method('canSerialize')
            ->with(
                $this->isInstanceOf(RequestInterface::class)
            )
            ->willReturn(true);

        $serializerB->expects($this->once())
            ->method('__invoke')
            ->with(
                $this->isInstanceOf(RequestInterface::class)
            );

        /* @var RequestInterface $request */
        $request = $this->getMock(RequestInterface::class);

        $chain = new SerializerChain(
            $serializerA,
            $serializerB
        );

        $chain($request);
    }

    /**
     * @test
     */
    public function throwsSerializerNotFoundException()
    {
        $this->setExpectedException(SerializerNotFoundException::class);

        /* @var RequestInterface $request */
        $request = $this->getMock(RequestInterface::class);

        $chain = new SerializerChain();

        $chain($request);
    }
}
