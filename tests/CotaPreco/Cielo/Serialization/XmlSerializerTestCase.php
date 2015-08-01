<?php

namespace CotaPreco\Cielo\Serialization;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
abstract class XmlSerializerTestCase extends TestCase
{
    /**
     * @param string $expected
     * @param string $request
     */
    protected function assertRequestXmlEquals($expected, $request)
    {
        $this->assertXmlStringEqualsXmlString(
            $this->removeRootAttributes($expected),
            $this->removeRootAttributes($request)
        );
    }

    /**
     * @param  string $xml
     * @return string
     */
    private function removeRootAttributes($xml)
    {
        $document = new \DOMDocument();

        $document->loadXML($xml);

        $root = $document->documentElement;

        while ($root->attributes->length) {
            $root->removeAttribute($root->attributes->item(0)->nodeName);
        }

        return $document->saveXML();
    }
}
