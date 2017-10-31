<?php

namespace EC\Behat\PoetryExtension\Context\Services;

use PHPUnit\Framework\AssertionFailedError;

/**
 * Class Assert
 *
 * @package EC\Behat\PoetryExtension\Context\Services
 */
class Assert extends \PHPUnit_Framework_Assert
{
    /**
     * Assert that two XML messages are the same.
     *
     * @param string $expected
     * @param string $actual
     * @param string $root
     */
    public static function assertSameXml($expected, $actual, $root)
    {
        $doc1 = new \DOMDocument();
        $doc1->loadXML($actual);

        $doc2 = new \DOMDocument();
        $doc2->loadXML($expected);

        $element1 = $doc1->getElementsByTagName($root)->item(0);
        $element2 = $doc2->getElementsByTagName($root)->item(0);

        Assert::assertXmlStringEqualsXmlString($expected, $actual);
        Assert::assertEqualXMLStructure($element1, $element2);
    }

    /**
     * Assert that the expected XML is contained in the actual one.
     *
     * @param string $contains
     * @param string $target
     */
    public static function assertContainsXml($contains, $target)
    {
        $root = simplexml_load_string($contains)->getName();
        $element = simplexml_load_string($contains);
        $contains = self::elementToArray($element);

        $document = new \DOMDocument();
        $document->loadXML($target);
        $target = new \DOMXpath($document);
        $nodes = $target->evaluate('//'.key($contains));
        if ($nodes->length === 0) {
            throw new AssertionFailedError("No nodes names {$root} found in target XML.");
        }

        $found = false;
        foreach ($nodes as $node) {
            $element = simplexml_import_dom($node);
            $array = self::elementToArray($element);
            try {
                Assert::assertEquals($array, $contains);
                $found = true;
            } catch (\Exception $e) {
                // Do not catch as exception will be thrown later, in case.
            }
        }

        Assert::assertTrue($found, "Node not found.");
    }

    /**
     * @param \SimpleXMLElement $element
     *
     * @return array
     */
    protected static function elementToArray(\SimpleXMLElement $element)
    {
        $json = json_encode($element);
        $array = json_decode($json, true);

        return [$element->getName() => $array];
    }
}
