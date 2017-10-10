<?php

namespace EC\Behat\PoetryExtension\Context;

use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

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
    public function setServerResponseWithXml(PyStringNode $string)
    {
        $this->setResponse($string->getRaw());
    }

    /**
     * @param string                           $name
     * @param \Behat\Gherkin\Node\PyStringNode $string
     *
     * @Given that Poetry will return the following :name message response:
     */
    public function setServerResponseWithMessage($name, PyStringNode $string)
    {
        $values = $this->parse($string);
        $message = $this->poetry->get($name)->withArray($values);
        $rendered = $this->poetry->getRenderer()->render($message);
        $this->setResponse($rendered);
    }

    /**
     * @param \Behat\Gherkin\Node\PyStringNode $string
     *
     * @Given that Poetry notifies the client with the following XML:
     */
    public function notifyClientWithXml(PyStringNode $string)
    {
        $this->poetryMock->sendNotification($string->getRaw());
    }

    /**
     * @param \Behat\Gherkin\Node\TableNode $table
     *
     * @Then client response contains the following text:
     */
    public function assertNotificationResponse(TableNode $table)
    {
        foreach ($table->getRows() as $row) {
            $this->assertContains($row[0], $this->poetryMock->getNotificationResponse());
        }
    }

    /**
     * @param string                           $name
     * @param \Behat\Gherkin\Node\PyStringNode $string
     *
     * @Given that Poetry notifies the client with the following :name message:
     */
    public function notifyClientWithMessage($name, PyStringNode $string)
    {
        $values = $this->parse($string);
        $message = $this->poetry->get($name)->withArray($values);
        $rendered = $this->poetry->getRenderer()->render($message);
        $this->poetryMock->sendNotification($rendered);
    }

    /**
     * @param string $response
     */
    protected function setResponse($response)
    {
        $this->getPoetryMock()->setResponse($this->poetryParameters['server']['endpoint'], $response);
    }
}
