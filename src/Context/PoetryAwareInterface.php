<?php
namespace EC\Behat\PoetryExtension\Context;

use Behat\Behat\Context\Context;
use EC\Poetry\Poetry;

/**
 * Interface PoetryAwareInterface
 *
 * @package EC\Behat\PoetryExtension\Context
 */
interface PoetryAwareInterface extends Context
{
    /**
     * Get Poetry property.
     *
     * @return \EC\Poetry\Poetry
     *   Property value.
     */
    public function getPoetry();

    /**
     * @param \EC\Poetry\Poetry $poetry
     *
     * @return mixed
     */
    public function setPoetry(Poetry $poetry);

    /**
     * Get PoetryParameters property.
     *
     * @return array
     *   Property value.
     */
    public function getPoetryParameters();

    /**
     * Sets parameters provided for Poetry.
     *
     * @param array $parameters
     */
    public function setPoetryParameters(array $parameters);
}
