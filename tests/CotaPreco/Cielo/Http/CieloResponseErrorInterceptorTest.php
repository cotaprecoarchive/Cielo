<?php

namespace CotaPreco\Cielo\Http;

use CotaPreco\Cielo\CieloEnvironment;
use CotaPreco\Cielo\Exception\CieloException;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class CieloResponseErrorInterceptorTest extends TestCase
{
    /**
     * @test
     */
    public function invoke()
    {
        $this->setExpectedException(CieloException::class);

        /* @var \PHPUnit_Framework_MockObject_MockObject|CieloHttpClientInterface $wrappedClient */
        $wrappedClient = $this->getMock(CieloHttpClientInterface::class);

        $wrappedClient->expects($this->once())
            ->method('__invoke')
            ->with(
                $this->isType('int'),
                $this->isType('string')
            )
            ->willReturn(
                <<<XML
<erro>
    <codigo>003</codigo>
    <mensagem>
    <![CDATA[Transação inexistente]]>
    </mensagem>
</erro>
XML
            );

        $interceptor = new CieloResponseErrorInterceptor($wrappedClient);

        $interceptor(
            CieloEnvironment::DEVELOPMENT,
            '<mensagem></mensagem>'
        );
    }

    /**
     * @test
     */
    public function delegatesIfNotAnError()
    {
        /* @var \PHPUnit_Framework_MockObject_MockObject|CieloHttpClientInterface $wrappedClient */
        $wrappedClient = $this->getMock(CieloHttpClientInterface::class);

        $wrappedClient->expects($this->once())
            ->method('__invoke')
            ->with(
                $this->isType('int'),
                $this->isType('string')
            )
            ->willReturn(
                '<resposta></resposta>'
            );

        $interceptor = new CieloResponseErrorInterceptor($wrappedClient);

        $response = $interceptor(
            CieloEnvironment::DEVELOPMENT,
            '<mensagem></mensagem>'
        );

        $this->assertEquals('<resposta></resposta>', $response);
    }
}
