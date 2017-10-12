<?php

namespace EC\Behat\PoetryExtension\Context\Services;

/**
 * Class PoetryMockHandler
 *
 * @package EC\Behat\PoetryExtension\Context\Services
 */
class PoetryMockHandler
{
    /**
     * @var string
     */
    private $body;

    /**
     * Service constructor.
     *
     * @param string $body
     */
    public function __construct($body)
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function requestService()
    {
        return $this->body;
    }
}
