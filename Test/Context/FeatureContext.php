<?php

namespace Infinity\Bundle\TestBundle\Test\Context;

use Behat\Behat\EventDispatcher\Event\GherkinNodeTested;
use Behat\MinkExtension\Context\MinkContext,
    Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Infinity\Bundle\TestBundle\Test\Helper\DatabaseHelper;
use Infinity\Bundle\TestBundle\Test\Helper\ScreenshotHelper;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;
//use Behat\CommonContexts\MinkExtraContext,
//    Behat\CommonContexts\MinkRedirectContext,
//    Behat\CommonContexts\SymfonyMailerContext;

//
// Require 3rd-party libraries here:
//
//   require_once 'src/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends RawMinkContext implements KernelAwareContext
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * Initializes context.
     * Every scenario gets its own context object.
     */
    public function __construct()
    {
        $this->useContext('mink', new MinkContext);

        // Removed until common context is upgrade to behat 3.0
        //$this->useContext('mink_extra', new MinkExtraContext());
        //$this->useContext('mink_redirect', new MinkRedirectContext());
        //$this->useContext('symfony_mailer', new SymfonyMailerContext());

        // Loads all php files under features/bootstrap iterating nested folder
        $finder = new Finder();
        $finder->files('*.php')->in(__DIR__.'/../../../../../../../../features/bootstrap');
        foreach ($finder as $file) {
            require_once ($file->getRealPath());
            $class = $file->getBasename('.php');
            $this->useContext($class, new $class());
        }
    }

    /**
     * @AfterStep
     *
     * @param StepEvent $event
     */
    public function getScreenshotOnFailure(StepEvent $event)
    {
        // 4 is the exit code for failing steps && We can get a screenshot only if Selenium2 is used && we have recipients
        if (4 === $event->getResult() &&
            $this->getSession()->getDriver() instanceof \Behat\Mink\Driver\Selenium2Driver &&
            $this->kernel->getContainer()->hasParameter('infinity_test.recipients')
        ) {
            $helper = new ScreenshotHelper($this->kernel);
            $helper->getScreenshot($this->getSession()->getDriver(), $event->getStep()->getText());
        }
    }

    /**
     * @BeforeScenario
     */
    public function setUpDB(GherkinNodeTested $event)
    {
        $scenario = $event->getNode();

        if ($scenario->hasTag('db')) {
            $helper = new DatabaseHelper($this->kernel);
            $helper->setUpDatabase();
        }
    }

    /**
     * @AfterScenario
     */
    public function tearDownDB(GherkinNodeTested $event)
    {
        $scenario = $event->getNode();

        if ($scenario->hasTag('db')) {
            // Drop the test db
            $helper = new DatabaseHelper($this->kernel);
            $helper->tearDownDatabase();
        }
    }

    /**
     * Sets Kernel instance.
     *
     * @param KernelInterface $kernel HttpKernel instance
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
}
