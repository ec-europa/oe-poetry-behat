<?php

namespace EC\Behat\PoetryExtension\Tests\Context\Services;

use EC\Behat\PoetryExtension\Context\Services\PoetryMock;
use PHPUnit\Framework\TestCase;

/**
 * Class PoetryMockTest
 *
 * @package EC\Behat\PoetryExtension\Tests\Context\Services
 */
class PoetryMockTest extends TestCase
{
    /**
     * @var PoetryMock
     */
    private $mock;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->mock = new PoetryMock();
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
        $this->mock->setResponse('mocked body');
        $this->assertSame('mocked body', $this->mock->getClient()->post('http://localhost:8082/foo')->send()->getBody(true));
    }
}
