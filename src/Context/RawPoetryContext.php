<?php

namespace EC\Behat\PoetryExtension\Context;

use EC\Behat\PoetryExtension\Context\Services\PoetryMock;
use EC\Poetry\Poetry;
use InterNations\Component\HttpMock\PHPUnit\HttpMockTrait;
use Behat\Gherkin\Node\PyStringNode;
use InterNations\Component\HttpMock\Request\UnifiedRequest;
use Symfony\Component\Yaml\Yaml;

/**
 * Class RawPoetryContext
 *
 * @package EC\Behat\PoetryExtension\Context
 */
class RawPoetryContext extends \PHPUnit_Framework_Assert implements PoetryAwareInterface
{
    use HttpMockTrait;

    /**
     * @var \EC\Poetry\Poetry
     */
    protected $poetry;

    /**
     * @var \EC\Behat\PoetryExtension\Context\Services\PoetryMock
     */
    protected $poetryMock;

    /**
     * {@inheritdoc}
     */
    public function setPoetry(Poetry $poetry)
    {
        $this->poetry = $poetry;
    }

    /**
     * {@inheritdoc}
     */
    public function setPoetryParameters(array $parameters)
    {
        $this->poetryMock->setParameters($parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function getPoetry()
    {
        return $this->poetry;
    }

    /**
     * {@inheritdoc}
     */
    public function getPoetryParameters()
    {
        return $this->poetryMock->getParameters();
    }

    /**
     * {@inheritdoc}
     */
    public function getPoetryMock()
    {
        return $this->poetryMock;
    }

    /**
     * {@inheritdoc}
     */
    public function setPoetryMock(PoetryMock $poetryMock)
    {
        $this->poetryMock = $poetryMock;
    }

    /**
     * Parse YAML contained in a PyString node.
     *
     * @param \Behat\Gherkin\Node\PyStringNode $node
     *
     * @return mixed
     */
    protected function parse(PyStringNode $node)
    {
        // Sanitize PyString test by removing initial indentation spaces.
        $strings = $node->getStrings();
        if (!empty($strings)) {
            preg_match('/^(\s+)/', $strings[0], $matches);
            $indentation = isset($matches[1]) ? strlen($matches[1]) : 0;
            foreach ($strings as $key => $string) {
                $strings[$key] = substr($string, $indentation);
            }
        }
        $raw = implode("\n", $strings);

        return Yaml::parse($raw);
    }

    /**
     * Assert that two XML messages are the same.
     *
     * @param string $expected
     * @param string $actual
     */
    protected function assertSameXml($expected, $actual)
    {
        $doc1 = new \DOMDocument();
        $doc1->loadXML($actual);

        $doc2 = new \DOMDocument();
        $doc2->loadXML($expected);

        $element1 = $doc1->getElementsByTagName('POETRY')->item(0);
        $element2 = $doc2->getElementsByTagName('POETRY')->item(0);

        $this->assertXmlStringEqualsXmlString($expected, $actual);
        $this->assertEqualXMLStructure($element1, $element2);
    }

    /**
     * @param \InterNations\Component\HttpMock\Request\UnifiedRequest $request
     * @param \Behat\Gherkin\Node\PyStringNode $string
     */
    protected function assertRequest(UnifiedRequest $request, PyStringNode $string)
    {
        $parser = $this->poetry->get('parser');
        $parser->addXmlContent((string) $request->getBody());
        $message = $parser->getContent('SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:requestService/msg');
        $message = htmlspecialchars_decode($message);
        $this->assertSameXml($string->getRaw(), $message);
    }
}
