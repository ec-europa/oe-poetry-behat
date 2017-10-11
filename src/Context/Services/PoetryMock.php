<?php

namespace EC\Behat\PoetryExtension\Context\Services;

use EC\Poetry\Poetry;
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
     * @var \EC\Poetry\Poetry
     */
    private $poetry;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var string
     */
    private $notificationResponse;

    /**
     * PoetryMock constructor.
     *
     * @param \EC\Poetry\Poetry $poetry
     * @param array             $parameters
     */
    public function __construct(Poetry $poetry, array $parameters)
    {
        $this->poetry = $poetry;
        $this->parameters = $parameters;
    }

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
     * Get Parameters property.
     *
     * @return array
     *   Property value.
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Set Parameters property.
     *
     * @param array $parameters
     *   Property value.
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Set response body.
     *
     * @param string $endpoint
     * @param string $body
     */
    public function setResponse($endpoint, $body)
    {
        $this->http->mock
          ->when()
          ->methodIs('POST')
          ->pathIs($endpoint)
          ->then()
          ->body($body)
          ->end();
        $this->http->setUp();
    }

    /**
     * Send Poetry notification to notification endpoint.
     *
     * @param string $message
     *
     * @return mixed
     */
    public function sendNotification($message)
    {
        $url = $this->parameters['application']['base_url'].$this->parameters['application']['endpoint'];
        $rendered = $this->poetry->getRenderEngine()->render('wsdl', ['callback' => $url]);
        $wsdl = 'data://text/plain;base64,'.base64_encode($rendered);
        $client = new \SoapClient($wsdl, ['cache_wsdl' => WSDL_CACHE_NONE]);

        $this->notificationResponse = $client->__soapCall('handle', [
            $this->parameters['service']['username'],
            $this->parameters['service']['password'],
            $message,
        ]);
    }

    /**
     * Get NotificationResponse property.
     *
     * @return string
     *   Property value.
     */
    public function getNotificationResponse()
    {
        return $this->notificationResponse;
    }
}
