<?php

namespace EC\Behat\PoetryExtension\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;

use Behat\Mink\Mink;
use Behat\MinkExtension\Context\MinkAwareContext;
use EC\Behat\PoetryExtension\Context\PoetryAwareInterface;
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
     * @var array
     */
    private $parameters;

    /**
     * PoetryAwareInitializer constructor.
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
     * {@inheritdoc}
     */
    public function initializeContext(Context $context)
    {
        if (!$context instanceof PoetryAwareInterface) {
            return;
        }

        $context->setPoetry($this->poetry);
        $context->setPoetryParameters($this->parameters);
    }
}
