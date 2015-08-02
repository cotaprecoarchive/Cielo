<?php

namespace CotaPreco\Cielo\Serialization\Request\Xml;

/**
 * @author Andrey K. Vital <andreykvital@gmail.com>
 */
abstract class SerializerTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $expected
     * @param string $actual
     */
    public function assertXmlEqualsIgnoringRootAttributes($expected, $actual)
    {
        $this->assertXmlStringEqualsXmlString(
            $this->removeRootAttributes($expected),
            $this->removeRootAttributes($actual)
        );
    }

    /**
     * @param  string $root
     * @return string
     */
    private function removeRootAttributes($root)
    {
        $document = new \DOMDocument();

        $document->loadXML($root);

        $top = $document->documentElement;

        while ($top->attributes->length) {
            /* @var \DOMAttr $attribute */
            $attribute = $top->attributes->item(0);

            $top->removeAttribute($attribute->nodeName);
        }

        return $document->saveXML();
    }
}
