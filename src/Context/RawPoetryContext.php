<?php

namespace EC\Behat\PoetryExtension\Context;

use EC\Behat\PoetryExtension\Context\Services\Assert;
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
class RawPoetryContext implements PoetryAwareInterface
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
     * @param \InterNations\Component\HttpMock\Request\UnifiedRequest $request
     * @param \Behat\Gherkin\Node\PyStringNode $string
     */
    protected function assertRequest(UnifiedRequest $request, PyStringNode $string)
    {
        $parser = $this->poetry->get('parser');
        $parser->addXmlContent((string) $request->getBody());
        $message = $parser->getContent('SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:requestService/msg');
        $message = htmlspecialchars_decode($message);
        Assert::assertSameXml($string->getRaw(), $message, 'POETRY');
    }
}
