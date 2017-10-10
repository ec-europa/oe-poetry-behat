<?php

namespace EC\Behat\PoetryExtension\Tests;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use EC\Behat\PoetryExtension\Context\RawPoetryContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use EC\Poetry\Poetry;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class FeatureContext
 *
 * @package EC\Behat\PoetryExtension\Tests
 */
class FeatureContext extends RawPoetryContext
{
    /**
     * @var \Guzzle\Http\Message\Response
     */
    private $response = null;

    /**
     * @var string
     */
    private $log = __DIR__.'/test.log';

    /**
     * @param \Behat\Behat\Hook\Scope\BeforeScenarioScope $scope
     *
     * @BeforeScenario @poetry
     */
    public function beforeScenario(BeforeScenarioScope $scope)
    {
        @unlink($this->log);
        $this->setupTestApplication();
    }

    /**
     * @When the test application sends a request to Poetry
     */
    public function sendGenericRequest()
    {
        $this->response = $this->getPoetryMock()
          ->getClient()
          ->post($this->getServerUrl())
          ->send();
    }

    /**
     * @param \Behat\Gherkin\Node\PyStringNode $string
     *
     * @Then the test application should receive the following response:
     */
    public function assertLatestXmlResponse(PyStringNode $string)
    {
        $expected = $string->getRaw();
        if ($this->response === null) {
            throw new \InvalidArgumentException('No request performed yet');
        }
        $actual = $this->response->getBody(true);
        $this->assertSameXml($expected, $actual);
    }

    /**
     * @param \Behat\Gherkin\Node\TableNode $table
     *
     * @Then the test application log should contain the following entries:
     */
    public function assertLogEntriesPresence(TableNode $table)
    {
        $content = file_get_contents($this->log);
        foreach ($table->getRows() as $row) {
            $this->assertContains($row[0], $content);
        }
    }

    /**
     * @param \Behat\Gherkin\Node\TableNode $table
     *
     * @Then the test application log should not contain the following entries:
     */
    public function assertLogEntriesAbsence(TableNode $table)
    {
        $content = file_get_contents($this->log);
        foreach ($table->getRows() as $row) {
            $this->assertNotContains($row[0], $content);
        }
    }

    /**
     * Setup test application.
     */
    protected function setupTestApplication()
    {
        $parameters = $this->getPoetryParameters();
        $mock = $this->getPoetryMock();
        $filename = $this->log;

        $callback = function (Response $response) use ($parameters, $filename) {
            $logger = new Logger('TestApplication');
            $logger->pushHandler(new StreamHandler($filename));
            $poetry = new Poetry([
                'notification.username' => $parameters['client']['username'],
                'notification.password' => $parameters['client']['password'],
                'logger' => $logger,
            ]);
            $poetry->getServer()->handle();
        };

        $mock->getHttp()->mock
          ->when()
          ->methodIs('POST')
          ->pathIs($parameters['client']['endpoint'])
          ->then()
          ->callback($callback)
          ->end();
        $mock->getHttp()->setUp();
    }

    /**
     * @return string
     */
    protected function getServerUrl()
    {
        $server = $this->getPoetryParameters()['server'];

        return sprintf('http://%s:%s%s', $server['host'], $server['port'], $server['endpoint']);
    }
}
