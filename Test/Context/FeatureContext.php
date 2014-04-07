<?php

namespace Infinity\Bundle\TestBundle\Test\Context;

use Behat\Behat\Event\BaseScenarioEvent;
use Behat\Behat\Event\OutlineExampleEvent;
use Behat\Behat\Event\ScenarioEvent;
use Behat\Behat\Event\StepEvent;
use Behat\MinkExtension\Context\MinkContext,
    Behat\MinkExtension\Context\RawMinkContext;
use Behat\CommonContexts\MinkExtraContext,
    Behat\CommonContexts\MinkRedirectContext,
    Behat\CommonContexts\SymfonyMailerContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Infinity\Bundle\TestBundle\Test\Helper\ScreenshotHelper;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\KernelInterface;
use RuntimeException;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends RawMinkContext implements KernelAwareInterface
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * Initializes context.
     * Every scenario gets its own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        $this->useContext('mink', new MinkContext);
        $this->useContext('mink_extra', new MinkExtraContext());
        $this->useContext('mink_redirect', new MinkRedirectContext());
        $this->useContext('symfony_mailer', new SymfonyMailerContext());

        // Loads all php files under features/bootstrap iterating nested folder
        $finder = new Finder();
        $finder->files('*.php')->in(__DIR__.'/../../../../../../../../features/bootstrap');
        foreach ($finder as $file) {
            require_once ($file->getRealPath());
            $class = $file->getBasename('.php');
            $this->useContext($class, new $class($parameters));
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
    public function setUpDB(BaseScenarioEvent $event)
    {
        if ($event instanceof OutlineExampleEvent) {
            $scenario = $event->getOutline();
        } else if ($event instanceof ScenarioEvent) {
            $scenario = $event->getScenario();
        }

        if ($scenario->hasTag('db')) {
            // Start with dropping the db, in case a previous error stopped the execution
            exec($this->kernel->getRootDir().'/console doctrine:database:drop --env=test --force -n');
            // Create a new instance of the DB
            exec($this->kernel->getRootDir().'/console doctrine:database:create --env=test -n', $output, $ret);
            if (0 == $ret) {
                //load migrations
                exec($this->kernel->getRootDir().'/console doctrine:migrations:migrate --env=test -n');
            } else {
                throw new RuntimeException('An error has prevented the creation of the test DB, please check your configuration');
            }
        }
    }

    /**
     * @AfterScenario
     */
    public function tearDownDB(BaseScenarioEvent $event)
    {
        if ($event instanceof OutlineExampleEvent) {
            $scenario = $event->getOutline();
        } else if ($event instanceof ScenarioEvent) {
            $scenario = $event->getScenario();
        }

        if ($scenario->hasTag('db')) {
            // Drop the test db
            exec($this->kernel->getRootDir().'/console doctrine:database:drop --env=test --force -n');
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
