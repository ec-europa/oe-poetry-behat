<?php
namespace EC\Behat\PoetryExtension\Context;

use Behat\Behat\Context\Context;
use EC\Behat\PoetryExtension\Context\Services\PoetryMock;
use EC\Poetry\Poetry;

/**
 * Interface PoetryAwareInterface
 *
 * @package EC\Behat\PoetryExtension\Context
 */
interface PoetryAwareInterface extends Context
{
    /**
     * @return \EC\Poetry\Poetry
     */
    public function getPoetry();

    /**
     * @param \EC\Poetry\Poetry $poetry
     */
    public function setPoetry(Poetry $poetry);

    /**
     * @return \EC\Behat\PoetryExtension\Context\Services\PoetryMock
     */
    public function getPoetryMock();

    /**
     * @param \EC\Behat\PoetryExtension\Context\Services\PoetryMock $poetryMock
     */
    public function setPoetryMock(PoetryMock $poetryMock);

    /**
     * @return array
     */
    public function getPoetryParameters();

    /**
     * @param array $parameters
     */
    public function setPoetryParameters(array $parameters);
}
