<?php


namespace Infinity\Bundle\TestBundle\Test\Selenium2;

use Infinity\Bundle\TestBundle\Test\Helper\ScreenshotHelper;

/**
 * Class    Selenium2Trait
 * @package Infinity\Bundle\TestBundle\Test\Selenium2
 *
 * This trait can be used by any phpunit TestCase that extends MinkTestCase and uses Selenium2 as Driver.
 * Provides a few useful methods to integrate in your TestCase.
 */
trait Selenium2Trait
{
    public function getScreenshotOnFailure()
    {
        $mink = $this->getMink();
        if ($this->hasFailed() && $mink->hasSession('selenium2')) {
            $helper = new ScreenshotHelper($this->getKernel());
            $helper->getScreenshot($mink->getSession('selenium2')->getDriver(), $this->getName());
        }
    }
}
