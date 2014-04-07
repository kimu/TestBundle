<?php

namespace Infinity\Bundle\TestBundle\Test;

use Behat\MinkBundle\Test\MinkTestCase;
use Infinity\Bundle\TestBundle\Test\Selenium2\Selenium2Trait;

class BaseTestCase extends MinkTestCase
{
    use Selenium2Trait;

    public function setUp()
    {
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->getScreenshotOnFailure();
    }
}
