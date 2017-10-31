<?php

namespace EC\Behat\PoetryExtension\Tests;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use EC\Behat\PoetryExtension\Context\RawPoetryContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use EC\Behat\PoetryExtension\Context\Services\Assert;
use EC\Poetry\Poetry;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class FeatureContext
 *
 * @package EC\Behat\PoetryExtension\Tests
 */
class FeatureContext extends RawPoetryContext
{
    /**
     * @var \EC\Poetry\Messages\Responses\AbstractResponse
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
        Assert::assertSameXml($expected, $this->response->getRaw(), 'POETRY');
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
            Assert::assertContains($row[0], $content);
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
            Assert::assertNotContains($row[0], $content);
        }
    }

    /**
     * @param string                           $name
     * @param \Behat\Gherkin\Node\PyStringNode $string
     *
     * @When the test application sends the following :name message to Poetry:
     */
    public function sendMessage($name, PyStringNode $string)
    {
        /** @var \EC\Poetry\Messages\MessageInterface $message */
        $poetry = $this->poetry;
        $wsdl = $this->poetryMock->getServiceUrl('/wsdl');
        $poetry->getSettings()->set('service.wsdl', $wsdl);
        $message = $poetry->get($name)->withArray($this->parse($string));
        $this->response = $poetry->getClient()->send($message);
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
                'notification.username' => $parameters['service']['username'],
                'notification.password' => $parameters['service']['password'],
                'logger' => $logger,
                'log_level' => LogLevel::DEBUG,
            ]);
            $poetry->getServer()->handle();
        };

        $mock->getHttp()->mock
          ->when()
          ->methodIs('POST')
          ->pathIs($parameters['application']['endpoint'])
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
        $service = $this->getPoetryParameters()['service'];

        return sprintf('http://%s:%s%s', $service['host'], $service['port'], $service['endpoint']);
    }
}
