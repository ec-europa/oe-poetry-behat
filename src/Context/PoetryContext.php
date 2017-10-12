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
     * @var array
     */
    private $backupParameters = [];

    /**
     * @param \Behat\Behat\Hook\Scope\BeforeScenarioScope $scope
     *
     * @BeforeScenario @poetry
     */
    public function beforeScenario(BeforeScenarioScope $scope)
    {
        $this->getPoetryMock()->setUp();
        $this->backupParameters = $this->getPoetryParameters()['service'];
    }

    /**
     * @param \Behat\Behat\Hook\Scope\AfterScenarioScope $scope
     *
     * @AfterScenario @poetry
     */
    public function afterScenario(AfterScenarioScope $scope)
    {
        $this->setPoetryParameters($this->backupParameters);
        $this->getPoetryMock()->tearDown();
    }

    /**
     * @param \Behat\Gherkin\Node\PyStringNode $string
     *
     * @Given Poetry service uses the following settings:
     */
    public function overrideServiceParameters(PyStringNode $string)
    {
        $parameters = $this->getPoetryParameters();
        $parameters['service'] = array_merge($parameters['service'], $this->parse($string));
        $this->setPoetryParameters($parameters);
    }

    /**
     * @param \Behat\Gherkin\Node\PyStringNode $string
     *
     * @Given Poetry will return the following XML response:
     */
    public function setServerResponseWithXml(PyStringNode $string)
    {
        $this->setResponse($string->getRaw());
    }

    /**
     * @param string                           $name
     * @param \Behat\Gherkin\Node\PyStringNode $string
     *
     * @Given Poetry will return the following :name message response:
     */
    public function setServerResponseWithMessage($name, PyStringNode $string)
    {
        $this->setResponse($this->getRenderedMessage($name, $string));
    }

    /**
     * @param \Behat\Gherkin\Node\PyStringNode $string
     *
     * @Given Poetry notifies the client with the following XML:
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
     * @Given Poetry notifies the client with the following :name message:
     */
    public function notifyClientWithMessage($name, PyStringNode $string)
    {
        $this->poetryMock->sendNotification($this->getRenderedMessage($name, $string));
    }

    /**
     * @param string $response
     */
    protected function setResponse($response)
    {
        $this->getPoetryMock()->setResponse($this->getPoetryParameters()['service']['endpoint'], $response);
    }

    /**
     * @param string                           $name
     * @param \Behat\Gherkin\Node\PyStringNode $string
     *
     * @return string
     */
    protected function getRenderedMessage($name, PyStringNode $string)
    {
        $values = $this->parse($string);
        $message = $this->poetry->get($name)->withArray($values);

        return $this->poetry->getRenderer()->render($message);
    }
}
