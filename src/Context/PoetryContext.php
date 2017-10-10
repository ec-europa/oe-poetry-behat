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
        $parameters = $this->getPoetryParameters();
        $this->getPoetryMock()->setUp($parameters['server']['port'], $parameters['server']['host']);
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
        $parameters = $this->getPoetryParameters();
        $this->getPoetryMock()->setResponse($parameters['server']['endpoint'], $string->getRaw());
    }

    /**
     * @param string                           $name
     * @param \Behat\Gherkin\Node\PyStringNode $string
     *
     * @Given that Poetry will return the following :name message response:
     */
    public function setupServerWithMessageResponse($name, PyStringNode $string)
    {
        $parameters = $this->getPoetryParameters();
        $values = $this->parse($string);
        /** @var \EC\Poetry\Messages\MessageInterface $message */
        $message = $this->getPoetry()->get($name)->withArray($values);
        $rendered = $this->getPoetry()->getRenderer()->render($message);
        $this->getPoetryMock()->setResponse($parameters['server']['endpoint'], $rendered);
    }
}
