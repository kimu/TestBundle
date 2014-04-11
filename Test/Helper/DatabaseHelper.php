<?php

namespace Infinity\Bundle\TestBundle\Test\Helper;

use Symfony\Component\HttpKernel\KernelInterface;
use \RuntimeException;

class DatabaseHelper
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function setUpDatabase()
    {
        // Start with dropping the db, in case a previous error stopped the execution
        $this->dropDB();
        // Create a new instance of the DB
        exec($this->kernel->getRootDir().'/console doctrine:database:create --env=test -n', $output, $ret);
        if (0 == $ret) {
            //load migrations
            exec($this->kernel->getRootDir().'/console doctrine:migrations:migrate --env=test -n');
        } else {
            throw new RuntimeException('An error has prevented the creation of the test DB, please check your configuration');
        }
    }

    public function tearDownDatabase()
    {
        $this->dropDB();
    }

    private function dropDB()
    {
        exec($this->kernel->getRootDir().'/console doctrine:database:drop --env=test --force -n');
    }
}
