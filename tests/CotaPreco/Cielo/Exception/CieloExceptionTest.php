<?php

namespace CotaPreco\Cielo\Exception;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class CieloExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function forCodeAndMessage()
    {
        $exception = CieloException::forCodeAndMessage(1, 'Mensagem inválida');

        $this->assertEquals(1, $exception->getCode());
        $this->assertEquals('Mensagem inválida', $exception->getMessage());
    }
}
