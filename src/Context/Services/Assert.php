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
     * @param string $expected
     * @param string $actual
     */
    public static function assertContainsXml($expected, $actual)
    {
        $document = new \DOMDocument();
        $document->loadXML($expected);
        $xpathExpected = new \DOMXpath($document);

        $document = new \DOMDocument();
        $document->loadXML($actual);
        $xpathActual = new \DOMXpath($document);

        $expressions = [];
        /** @var \DOMNode $node */
        foreach ($xpathExpected->evaluate('//*[count(*) = 0]|//@*') as $node) {
            $expression = $node->getNodePath();
            $expressions[] = $expression;

            if ($xpathActual->query($expression)->length == 0) {
                throw new AssertionFailedError("Expression {$expression} not found.");
            }
            $actualNode = $xpathActual->evaluate($expression)->item(0);
            if ($node->textContent !== $actualNode->textContent) {
                throw new AssertionFailedError("Expression {$expression}: {$node->textContent} is not equal {$actualNode->textContent}.");
            }
        }
    }
}
