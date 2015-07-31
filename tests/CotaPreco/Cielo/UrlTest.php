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
}
