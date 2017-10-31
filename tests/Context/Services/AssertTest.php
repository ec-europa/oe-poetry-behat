<?php

namespace EC\Behat\PoetryExtension\Tests\Context\Services;

use EC\Behat\PoetryExtension\Context\Services\Assert;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

/**
 * Class AssertTest
 *
 * @package EC\Behat\PoetryExtension\Tests\Context\Services
 */
class AssertTest extends TestCase
{
    /**
     * Test Assert::assertContainsXml().
     *
     * @param string $target
     * @param string $contains
     * @param bool   $expected
     *
     * @dataProvider containsProvider
     */
    public function testAssertContainsXml($target, $contains, $expected)
    {
        $actual = true;
        $message = '';
        try {
            Assert::assertContainsXml($contains, $target);
        } catch (\Exception $e) {
            $actual = false;
            $message = $e->getMessage();
        }

        $this->assertEquals($expected, $actual, $message);
    }

    /**
     * @return array
     */
    public function containsProvider()
    {
        return Yaml::parse(file_get_contents(__DIR__.'/../../fixtures/contains.yml'));
    }
}
