<?php

use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext,
    Behat\MinkExtension\Context\RawMinkContext;
//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends RawMinkContext
{
    /**
     * Initializes context.
     * Every scenario gets its own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        $this->useContext('mink', new MinkContext);

        // Loads all php files under features/bootstrap iterating nested folder
        $iterator = new RecursiveDirectoryIterator(__DIR__);
        while ($iterator->valid()) {
            $file = $iterator->current();
            if (!$file->isDir() && __CLASS__ != $class = $file->getBasename('.php')) {
                $this->useContext($class, new $class($parameters));
            }
            $iterator->next();
        }
    }
}
