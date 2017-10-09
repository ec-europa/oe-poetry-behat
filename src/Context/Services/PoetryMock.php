<?php

namespace EC\Behat\PoetryExtension\Context\Services;

use InterNations\Component\HttpMock\PHPUnit\HttpMockTrait;

/**
 * Class PoetryMock
 *
 * @package EC\Behat\PoetryExtension\Context\Services
 */
class PoetryMock extends \PHPUnit_Framework_Assert
{
    use HttpMockTrait;

    /**
     * Setup HTTP server, to be used in @BeforeScenario callbacks.
     *
     * @param string $port
     * @param string $host
     * @param string $basePath
     * @param string $name
     */
    public function setUp($port = null, $host = null, $basePath = null, $name = null)
    {
        static::setUpHttpMockBeforeClass($port, $host, $basePath, $name);
        $this->setUpHttpMock();
    }

    /**
     * Tear down HTTP server, to be used in @AfterScenario callbacks.
     */
    public function tearDown()
    {
        $this->tearDownHttpMock();
        static::tearDownHttpMockAfterClass();
    }

    /**
     * @return \InterNations\Component\HttpMock\PHPUnit\HttpMockFacade|\InterNations\Component\HttpMock\PHPUnit\HttpMockFacadeMap
     */
    public function getHttp()
    {
        return $this->http;
    }

    /**
     * @return \Guzzle\Http\Client
     */
    public function getClient()
    {
        return $this->http->client;
    }

    /**
     * Set response body.
     *
     * @param string $body
     */
    public function setResponse($body)
    {
        $this->http->mock->when()->methodIs('POST')->then()->body($body)->end();
        $this->http->setUp();
    }
}
