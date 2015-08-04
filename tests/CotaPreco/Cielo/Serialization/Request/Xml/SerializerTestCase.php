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
     * @param string[] $ignore
     * @param string   $expected
     * @param string   $actual
     */
    public function assertXmlIgnoringSpecifiedNodes($ignore, $expected, $actual)
    {
        $this->assertXmlStringEqualsXmlString(
            $this->removeRootAttributes(
                $expected
            ),
            $this->removeRootAttributes(
                $this->removeSpecifiedNodes($ignore, $actual)
            )
        );
    }

    /**
     * @param  string[] $nodes
     * @param  string   $xml
     * @return string
     */
    private function removeSpecifiedNodes($nodes, $xml)
    {
        $document = new \DOMDocument();

        $document->preserveWhiteSpace = false;

        $document->formatOutput = true;

        $document->loadXML($xml);

        foreach ($nodes as $tagName) {
            $node = $document->getElementsByTagName($tagName)->item(0);

            $node->parentNode->removeChild($node);
        }

        return $document->saveXML();
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
