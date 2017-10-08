<?php

namespace EC\Behat\PoetryExtension\Context;

use EC\Poetry\Poetry;

/**
 * Class RawPoetryContext
 *
 * @package EC\Behat\PoetryExtension\Context
 */
class RawPoetryContext implements PoetryAwareInterface
{
    /**
     * @var \EC\Poetry\Poetry
     */
    private $poetry;

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
}
