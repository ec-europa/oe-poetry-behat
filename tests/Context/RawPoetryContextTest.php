<?php

namespace EC\Behat\PoetryExtension\Tests\Context;

use EC\Behat\PoetryExtension\Context\RawPoetryContext;
use EC\Behat\PoetryExtension\Context\Services\PoetryMock;
use EC\Poetry\Poetry;
use PHPUnit\Framework\TestCase;

/**
 * Class RawPoetryContextTest.
 *
 * @package EC\Behat\PoetryExtension\Tests\Context
 */
class RawPoetryContextTest extends TestCase
{

    /**
     * Test tokens replacement.
     *
     * @param string $text
     * @param string $expected
     *
     * @dataProvider tokenTextProvider
     */
    public function testReplaceTokens($text, $expected)
    {
        $context = new RawPoetryContext();
        $poetry = new Poetry();
        $mock = new PoetryMock($poetry, [
            'application' => [
            'base_url' => 'http://localhost:8082',
            'endpoint' => '/notification',
            ],
            'service' => [
            'host' => 'localhost',
            'port' => '8082',
            'endpoint' => '/service',
            'wsdl' => '/wsdl',
            'username' => 'foo',
            'password' => 'bar',
            ],
        ]);
        $context->setPoetryMock($mock);

        $actual = $this->invokeMethod($context, 'replaceTokens', [$text]);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object $object
     *    Instantiated object that we will run method on.
     * @param string $methodName
     *    Method name to call.
     * @param array  $parameters
     *    Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * @return array
     */
    public function tokenTextProvider()
    {
        return [
          ['<test>!application.base_url!application.endpoint</test>', '<test>http://localhost:8082/notification</test>'],
          ['!service.host !service.port !service.username !service.password', 'localhost 8082 foo bar'],
        ];
    }
}
