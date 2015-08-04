<?php

namespace CotaPreco\Cielo\Unmarshalling\GeneratedToken;

use CotaPreco\Cielo\GeneratedToken;
use CotaPreco\Cielo\GeneratedTokenStatus;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class GeneratedTokenUnmarshallerTest extends TestCase
{
    /**
     * @var GeneratedTokenUnmarshaller
     */
    private $unmarshaller;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->unmarshaller = new GeneratedTokenUnmarshaller();
    }

    /**
     * @test
     */
    public function invoke()
    {
        $unmarshaller = $this->unmarshaller;

        $generatedToken = $unmarshaller(
            <<<RETORNO_TOKEN
<retorno-token>
    <token>
        <dados-token>
            <codigo-token>195d8d597612b822fd2c17b266c62896</codigo-token>
            <status>1</status>
            <numero-cartao-truncado>4***************</numero-cartao-truncado>
        </dados-token>
    </token>
</retorno-token>
RETORNO_TOKEN
        );

        $this->assertInstanceOf(GeneratedToken::class, $generatedToken);

        /* @var GeneratedToken $generatedToken */
        $this->assertSame(GeneratedTokenStatus::UNBLOCKED, $generatedToken->getStatus());
        $this->assertEquals('4***************', $generatedToken->getTruncatedCardNumber());
    }

    /**
     * @test
     */
    public function withBlockedToken()
    {
        $unmarshaller = $this->unmarshaller;

        $generatedToken = $unmarshaller(
            <<<RETORNO_TOKEN
<retorno-token>
    <token>
        <dados-token>
            <codigo-token>195d8d597612b822fd2c17b266c62896</codigo-token>
            <status>0</status>
            <numero-cartao-truncado>4***************</numero-cartao-truncado>
        </dados-token>
    </token>
</retorno-token>
RETORNO_TOKEN
        );

        $this->assertInstanceOf(GeneratedToken::class, $generatedToken);

        /* @var GeneratedToken $generatedToken */
        $this->assertSame(GeneratedTokenStatus::BLOCKED, $generatedToken->getStatus());
    }
}
