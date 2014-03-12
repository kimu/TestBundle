<?php

namespace Infinity\Bundle\TestBundle\Composer;

use Composer\Script\Event;

class ScriptHandler
{
    public static function installConfigurationFiles(Event $event)
    {
        $appDir = !empty($event->getComposer()->getPackage()->getExtra()['symfony-app-dir']) ? $event->getComposer()->getPackage()->getExtra()['symfony-app-dir'] : 'app';

        if (!is_dir($appDir)) {
            echo 'The symfony-app-dir ('.$appDir.') specified in composer.json was not found in '.getcwd().', can not install configuration files.'.PHP_EOL;

            return;
        }

        //Install configuration scripts only if they are missing
        if (!is_file($appDir.'/config/behat.yml.dist')) {
            copy(__DIR__.'/../Test/config/behat.yml.dist', $appDir.'/config/behat.yml.dist');
        }
        if (!is_file(($appDir.'/../config/phpspec.yml.dist'))) {
            copy(__DIR__.'/../Test/config/phpspec.yml.dist', $appDir.'/config/phpspec.yml.dist');
        }
    }

    public static function initBehat(Event $event)
    {
        // Init behat if it hasn't been already
        if (!is_dir('features')) {
            // check if behat has been correctly installed
            if (is_file('bin/behat')) {
                system('bin/behat --init');
                //Check that everything is correct before replacing FeatureContext.php
                if (is_dir('features') && is_dir('features/bootstrap')) {
                    copy(__DIR__.'/../Test/Context/FeatureContext.php', 'features/bootstrap/FeatureContext.php');
                } else {
                    echo 'Initialization of Behat failed, can not replace FeatureContext.php'.PHP_EOL;

                    return;
                }
            } else {
                echo 'Behat has not been found in '.getcwd().'/bin/behat, can not initialize Behat'.PHP_EOL;

                return;
            }
        }
    }
}
