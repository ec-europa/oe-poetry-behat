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
        $this->getPoetryMock()->setUp($parameters['mock']['port'], $parameters['mock']['host']);
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
     * @Given the following Poetry service response:
     */
    public function setupServerWithXmlResponse(PyStringNode $string)
    {
        $this->getPoetryMock()->setResponse($string->getRaw());
    }
}
