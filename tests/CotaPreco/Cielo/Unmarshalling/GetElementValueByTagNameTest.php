<?php

namespace CotaPreco\Cielo\Unmarshalling;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
class GetElementValueByTagNameTest extends TestCase
{
    /**
     * @test
     */
    public function invoke()
    {
        $document = new \DOMDocument();

        $root = $document->createElement('root');

        $root->appendChild(
            $document->createElement('node-a', 'foo')
        );

        $root->appendChild(
            $document->createElement('node-a', 'bar')
        );

        $document->appendChild($root);

        $getElementValue = GetElementValueByTagName::fromRootNode($root);

        $this->assertEquals('foo', $getElementValue('node-a'));
    }
}
