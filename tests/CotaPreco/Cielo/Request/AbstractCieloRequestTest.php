<?php

namespace CotaPreco\Cielo\Request;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class AbstractCieloRequestTest extends TestCase
{
    /**
     * @test
     */
    public function getShapeVersion()
    {
        /* @var AbstractCieloRequest $request */
        $request = $this->getMockForAbstractClass(AbstractCieloRequest::class);

        $this->assertEquals('1.2.1', $request->getShapeVersion());
    }

    /**
     * @test
     */
    public function getRequestId()
    {
        /* @var AbstractCieloRequest $request */
        $request = $this->getMockForAbstractClass(AbstractCieloRequest::class);

        $this->assertEmpty($request->getRequestId());
    }
}
