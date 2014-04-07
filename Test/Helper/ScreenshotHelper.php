<?php


namespace Infinity\Bundle\TestBundle\Test\Helper;

use Behat\Mink\Driver\DriverInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class ScreenshotHelper
{
    protected $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @param DriverInterface $driver   Selenium2 driver or any other driver that has a getScreenshot method
     * @param String          $testName Name of the failing test
     * @param bool $sendEmail Default true. True to send the email to infinity_tests.recipients
     *
     * @return string The screenshot filename
     */
    public function getScreenshot(DriverInterface $driver, $testName, $sendEmail = true)
    {
        $screenshot = $driver->getScreenshot();
        $file       = sys_get_temp_dir().'/selenium/'.date('Ymd_His').'_screen.png';

        // creates directories if necessary
        if (!@is_dir(dirname($file))) {
            @mkdir(dirname($file), 0755, true);
        }

        file_put_contents($file, $screenshot);

        if ($sendEmail && $this->kernel->getContainer()->hasParameter('infinity_test.recipients')) {
            // Prepare the email
            $body    = 'Screenshot of failing test: '.$testName;
            $subject = 'Failing test: '.$testName;

            if (false !== getenv('TRAVIS')) {
                $subject = 'TravisCI '.$subject;
                $body   .= PHP_EOL.
                    'Branch: '.getenv('TRAVIS_BRANCH').PHP_EOL.
                    'Commit: '.getenv('TRAVIS_COMMIT').PHP_EOL.
                    'Pull Request: '.getenv('TRAVIS_PULL_REQUEST').PHP_EOL.
                    'User/Repo: '.getenv('TRAVIS_REPO_SLUG')
                ;
            } else {
                exec('git status', $output);
                $body .= PHP_EOL.implode(PHP_EOL, $output);
            }

            // Send the email with the screenshot attached
            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom('server@'.gethostname())
                ->setTo($this->kernel->getContainer()->getParameter('infinity_test.recipients'))
                ->setBody($body)
                ->attach(\Swift_Attachment::fromPath($file))
            ;

            \Swift_Mailer::newInstance(\Swift_MailTransport::newInstance())->send($message);
        }

        return $file;
    }
}
