<?php

namespace Infinity\Bundle\TestBundle\Test\Doctrine;

use Infinity\Bundle\TestBundle\Test\Helper\DatabaseHelper;

/**
 * Class    DoctrineTrait
 * @package Infinity\Bundle\TestBundle\Test\Doctrine
 *
 * This trait can be used by any phpunit TestCase that needs a database.
 * Provides a few useful methods to integrate in your TestCase for setting up ad dropping database instances.
 */
trait DoctrineTrait
{
    public function setUpDatabase()
    {
        $helper = new DatabaseHelper($this->getKernel());
        $helper->setUpDatabase();
    }

    public function tearDownDatabase()
    {
        $helper = new DatabaseHelper($this->getKernel());
        $helper->tearDownDatabase();
    }
}
