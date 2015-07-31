<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class PanTest extends TestCase
{
    /**
     * @test
     */
    public function fromTokenString()
    {
        $token = 'IqVz7P9zaIgTYdU41HaW/OB/d7Idwttqwb2vaTt8MT0=';

        $this->assertEquals($token, (string) Pan::fromTokenString($token));
    }
}
