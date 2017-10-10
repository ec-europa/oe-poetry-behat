<?php

namespace EC\Behat\PoetryExtension\Tests;

use Behat\Gherkin\Node\PyStringNode;
use EC\Behat\PoetryExtension\Context\RawPoetryContext;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use EC\Poetry\Poetry;
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
     * @param \Behat\Behat\Hook\Scope\BeforeScenarioScope $scope
     *
     * @BeforeScenario @poetry
     */
    public function beforeScenario(BeforeScenarioScope $scope)
    {
        $parameters = $this->getPoetryParameters();
        $url = parse_url($parameters['client']['base_url']);
        $this->getPoetryMock()->setUp($url['port'], $url['host']);
        $this->setupTestApplication();
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
     * Setup test application.
     */
    protected function setupTestApplication()
    {
        $parameters = $this->getPoetryParameters();
        $mock = $this->getPoetryMock();

        $callback = function (Response $response) use ($parameters) {
            $poetry = new Poetry([
                'notification.username' => $parameters['client']['username'],
                'notification.password' => $parameters['client']['username'],
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
