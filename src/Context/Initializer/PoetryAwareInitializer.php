<?php

namespace EC\Behat\PoetryExtension\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use EC\Behat\PoetryExtension\Context\PoetryAwareInterface;
use EC\Behat\PoetryExtension\Context\Services\PoetryMock;
use EC\Poetry\Poetry;

/**
 * Class PoetryAwareInitializer
 *
 * @package Behat\MinkExtension\Context\Initializer
 */
class PoetryAwareInitializer implements ContextInitializer
{
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
    private $parameters;

    /**
     * PoetryAwareInitializer constructor.
     *
     * @param \EC\Poetry\Poetry                                     $poetry
     * @param \EC\Behat\PoetryExtension\Context\Services\PoetryMock $poetryMock
     * @param array                                                 $parameters
     */
    public function __construct(Poetry $poetry, PoetryMock $poetryMock, array $parameters)
    {
        $this->poetry = $poetry;
        $this->poetryMock = $poetryMock;
        $this->parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function initializeContext(Context $context)
    {
        if ($context instanceof PoetryAwareInterface) {
            $context->setPoetry($this->poetry);
            $context->setPoetryMock($this->poetryMock);
            $context->setPoetryParameters($this->parameters);
        }
    }
}
