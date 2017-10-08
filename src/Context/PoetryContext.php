<?php

namespace EC\Behat\PoetryExtension\Context;

use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use InterNations\Component\HttpMock\PHPUnit\HttpMockTrait;

/**
 * Class PoetryContext
 *
 * @package EC\Behat\PoetryExtension\Context
 */
class PoetryContext extends RawPoetryContext
{
    use HttpMockTrait;

    /**
     * @param \Behat\Behat\Hook\Scope\BeforeScenarioScope $scope
     *
     * @BeforeScenario @poetry
     */
    public function beforeScenario(BeforeScenarioScope $scope)
    {
        $parameters = $this->getPoetryParameters();
        static::setUpHttpMockBeforeClass($parameters['mock']['port'], $parameters['mock']['host']);
        $this->setUpHttpMock();
    }

    /**
     * @param \Behat\Behat\Hook\Scope\AfterScenarioScope $scope
     *
     * @AfterScenario @poetry
     */
    public function afterScenario(AfterScenarioScope $scope)
    {
        $this->tearDownHttpMock();
        static::tearDownHttpMockAfterClass();
    }

    /**
     * @param \Behat\Gherkin\Node\PyStringNode $string
     *
     * @Given the following Poetry service response:
     */
    public function setupServerWithXmlResponse(PyStringNode $string)
    {
        $this->setMockResponse($string->getRaw());
    }

    /**
     * @param string $expected
     * @param string $actual
     * @param string $message
     *
     * @throws \Exception
     */
    public static function assertSame($expected, $actual, $message = '')
    {
        if ($expected !== $actual) {
            throw new \Exception($message);
        }
    }

    /**
     * @param string $message
     *
     * @throws \Exception
     */
    public static function fail($message = '')
    {
        throw new \Exception($message);
    }

    /**
     * @param $body
     */
    protected function setMockResponse($body)
    {
        $parameters = $this->getPoetryParameters();
        $this->http->mock
          ->when()
          ->methodIs('POST')
          ->pathIs($parameters['poetry']['notification_endpoint'])
          ->then()
          ->body($body)
          ->end();
        $this->http->setUp();
    }
}
