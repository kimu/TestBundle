<?php

namespace Infinity\Bundle\TestBundle\Test\Context;

use Behat\Testwork\Hook\Scope\HookScope;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Testwork\Tester\Result\TestResult;
use Symfony\Component\Finder\Finder;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Symfony\Component\HttpKernel\KernelInterface;
use Infinity\Bundle\TestBundle\Test\Doctrine\DoctrineTrait;
use Infinity\Bundle\TestBundle\Test\Helper\ScreenshotHelper;
use Behat\Mink\Driver\Selenium2Driver;

/**
 * Base Features context.
 */
class BaseContext extends RawMinkContext implements KernelAwareContext
{
    use DoctrineTrait;

    /**
     * @var KernelInterface
     */
    protected static $kernel;

    /**
     * @AfterStep
     *
     * @param AfterStepScope $scope
     */
    public function getScreenshotOnFailure(AfterStepScope $scope)
    {
        // TestResult::FAILED is the exit code for failing steps && We can get a screenshot only if Selenium2 is used && we have recipients
        if (TestResult::FAILED === $scope->getTestResult()->getResultCode() &&
            $this->getSession()->getDriver() instanceof Selenium2Driver::class &&
            $this->getKernel()->getContainer()->hasParameter('infinity_test.recipients')
        ) {
            $helper = new ScreenshotHelper($this->getKernel());
            $helper->getScreenshot($this->getSession()->getDriver(), $scope->getStep()->getText());
        }
    }

    /**
     * @BeforeScenario @db,@dbup
     * @BeforeStep @db,@dbup
     */
    public static function setUpDB(HookScope $scope)
    {
        static::setUpDatabase();
    }

    /**
     * @AfterScenario @db,@dbdown
     * @AfterStep @db,@dbdown
     */
    public static function tearDownDB(HookScope $scope)
    {
        static::tearDownDatabase();
    }

    /**
     * Sets Kernel instance.
     *
     * @param KernelInterface $kernel HttpKernel instance
     */
    public function setKernel(KernelInterface $kernel)
    {
        static::$kernel = $kernel;
    }

    /**
     * Gets the kernel
     */
    public static function getKernel()
    {
        return static::$kernel;
    }
}
