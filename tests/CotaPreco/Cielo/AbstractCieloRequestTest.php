<?php

namespace CotaPreco\Cielo;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class AbstractCieloRequestTest extends TestCase
{
    /**
     * @var AbstractCieloRequest
     */
    protected $request;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->request = $this->getMockForAbstractClass(AbstractCieloRequest::class);
    }

    /**
     * @test
     */
    public function getRequestId()
    {
        $this->assertNotNull($this->request->getRequestId());
    }

    /**
     * @test
     */
    public function getShapeVersion()
    {
        $this->assertEquals('1.2.1', $this->request->getShapeVersion());
    }
}
