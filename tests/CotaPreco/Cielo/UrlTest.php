<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class UrlTest extends TestCase
{
    /**
     * @test
     */
    public function fromString()
    {
        $url = 'http://localhost/lojaexemplo/retorno.jsp';

        $this->assertEquals($url, (string) Url::fromString($url));
    }

    /**
     * @test
     * @param string $url
     * @dataProvider invalidUrls
     */
    public function throwsInvalidArgumentException($url)
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        Url::fromString($url);
    }

    /**
     * @test
     */
    public function invalidUrls()
    {
        return [
            [''],
            ['proto://'],
            [null],
            ['#invalid'],
            ['http://#invalid.com']
        ];
    }
}
