<?php

namespace EC\Behat\PoetryExtension\Tests\Context\Services;

use EC\Behat\PoetryExtension\Context\Services\PoetryMock;
use EC\Poetry\Poetry;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PoetryMockTest
 *
 * @package EC\Behat\PoetryExtension\Tests\Context\Services
 */
class PoetryMockTest extends TestCase
{
    /**
     * @var \EC\Behat\PoetryExtension\Context\Services\PoetryMock
     */
    private $mock;

    /**
     * @var \EC\Poetry\Poetry
     */
    private $poetry;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->poetry = new Poetry([
            'identifier.code' => 'DGT',
            'identifier.year' => '2017',
            'identifier.number' => '0001',
            'identifier.version' => '01',
            'identifier.part' => '00',
            'identifier.product' => 'ABC',
        ]);

        $this->mock = new PoetryMock($this->poetry, [
            'client' => [
              'base_url' => 'http://localhost:8082',
              'endpoint' => '/notification',
              'username' => 'foo',
              'password' => 'bar',
            ],
        ]);
        $this->mock->setUp('8082');
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->mock->tearDown();
    }

    /**
     * Test simple request.
     */
    public function testSimpleRequest()
    {
        $this->mock->setResponse('/service', 'mocked body');
        $response = $this->mock
          ->getClient()
          ->post('http://localhost:8082/service')
          ->send()
          ->getBody(true);
        $this->assertSame('mocked body', $response);
        $this->assertSame('POST', $this->mock->getHttp()->requests->latest()->getMethod());
        $this->assertSame('/service', $this->mock->getHttp()->requests->latest()->getPath());
    }
}
