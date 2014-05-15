<?php

namespace Infinity\Bundle\TestBundle\Test\Doctrine;

use Infinity\Bundle\TestBundle\Test\Helper\DatabaseHelper;

/**
 * Class    DoctrineTrait
 * @package Infinity\Bundle\TestBundle\Test\Doctrine
 *
 * This trait can be used by any phpunit TestCase that needs a database.
 * Provides a few useful methods to integrate in your TestCase for setting up ad dropping database instances.
 * You need to extend MinkTextCase in order to use this Trait, or you have to provide a getKernel method.
 */
trait DoctrineTrait
{
    public static function setUpDatabase()
    {
        $helper = new DatabaseHelper(static::getKernel());
        $helper->setUpDatabase();
    }

    public static function tearDownDatabase()
    {
        $helper = new DatabaseHelper(static::getKernel());
        $helper->tearDownDatabase();
    }
}
