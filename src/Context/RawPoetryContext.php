<?php

namespace EC\Behat\PoetryExtension\Context;

use EC\Behat\PoetryExtension\Context\Services\PoetryMock;
use EC\Poetry\Poetry;
use InterNations\Component\HttpMock\PHPUnit\HttpMockTrait;

/**
 * Class RawPoetryContext
 *
 * @package EC\Behat\PoetryExtension\Context
 */
class RawPoetryContext extends \PHPUnit_Framework_Assert implements PoetryAwareInterface
{
    use HttpMockTrait;

    /**
     * @var \EC\Poetry\Poetry
     */
    private $poetry;

    /**
     * @var \EC\Behat\PoetryExtension\Context\Services\PoetryMock
     */
    private $poetryMock;

    /**
     * @var array
     */
    private $poetryParameters;

    /**
     * {@inheritdoc}
     */
    public function setPoetry(Poetry $poetry)
    {
        $this->poetry = $poetry;
    }

    /**
     * {@inheritdoc}
     */
    public function setPoetryParameters(array $parameters)
    {
        $this->poetryParameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getPoetry()
    {
        return $this->poetry;
    }

    /**
     * {@inheritdoc}
     */
    public function getPoetryParameters()
    {
        return $this->poetryParameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getPoetryMock()
    {
        return $this->poetryMock;
    }

    /**
     * {@inheritdoc}
     */
    public function setPoetryMock(PoetryMock $poetryMock)
    {
        $this->poetryMock = $poetryMock;
    }
}
