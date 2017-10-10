<?php

namespace EC\Behat\PoetryExtension\Context;

use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;

/**
 * Class PoetryContext
 *
 * @package EC\Behat\PoetryExtension\Context
 */
class PoetryContext extends RawPoetryContext
{
    /**
     * @param \Behat\Behat\Hook\Scope\BeforeScenarioScope $scope
     *
     * @BeforeScenario @poetry
     */
    public function beforeScenario(BeforeScenarioScope $scope)
    {
        $server = $this->poetryParameters['server'];
        $this->getPoetryMock()->setUp($server['port'], $server['host']);
    }

    /**
     * @param \Behat\Behat\Hook\Scope\AfterScenarioScope $scope
     *
     * @AfterScenario @poetry
     */
    public function afterScenario(AfterScenarioScope $scope)
    {
        $this->getPoetryMock()->tearDown();
    }

    /**
     * @param \Behat\Gherkin\Node\PyStringNode $string
     *
     * @Given that Poetry will return the following XML response:
     */
    public function setupServerWithXmlResponse(PyStringNode $string)
    {
        $this->setResponse($string->getRaw());
    }

    /**
     * @param string                           $name
     * @param \Behat\Gherkin\Node\PyStringNode $string
     *
     * @Given that Poetry will return the following :name message response:
     */
    public function setupServerWithMessageResponse($name, PyStringNode $string)
    {
        $values = $this->parse($string);
        $message = $this->poetry->get($name)->withArray($values);
        $rendered = $this->poetry->getRenderer()->render($message);
        $this->setResponse($rendered);
    }

    /**
     * @param string $response
     */
    protected function setResponse($response)
    {
        $this->getPoetryMock()->setResponse($this->poetryParameters['server']['endpoint'], $response);
    }
}
