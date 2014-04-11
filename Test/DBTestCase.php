<?php

namespace Infinity\Bundle\TestBundle\Test;

use Infinity\Bundle\TestBundle\Test\Doctrine\DoctrineTrait;

class DBTestCase extends BaseTestCase
{
    use DoctrineTrait;

    public function setUp()
    {
        parent::setUp();
        $this->setUpDatabase();
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->tearDownDatabase();
    }
}
