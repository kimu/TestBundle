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
        if (!is_file(($appDir.'/config/phpspec.yml.dist'))) {
            copy(__DIR__.'/../Test/config/phpspec.yml.dist', $appDir.'/config/phpspec.yml.dist');
        }
    }
}
