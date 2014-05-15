#Testing with a DB

TestBundle provides a ready to use solution for testing with a DB.

First of all you need to provide a configuration for your DB to be used only in the test environment. This way you avoid to mess up with your development or production DB.

TestBundle creates a test DB relying on a `default` connection configuration. In config_test.yml create a connection configuration that can be used to create a local DB.

Example:

```yaml
# /app/config/config_test.yml

doctrine:
    dbal:
        driver:   pdo_mysql
        host:     localhost
        dbname:   test_db
        user:     root
        charset:  UTF8

```

## Behat and DB

There is one tag `@db` that can be used with Behat features to create and delete a db, usable both with scenarios or steps.
If a scenario or a step is tagged with `@db` the DB is first created and then deleted, ritght after the scenario or the step.

You can use what you find more comfortable to you to create data in DB. You can access Doctrine implementing the `KernelAwareInterface` in you Context class.

For massive data creation take a look to [https://github.com/fzaninotto/Faker](https://github.com/fzaninotto/Faker).

## PHPUnit and DB

If you need a DB to be set up for you in your PHPUnit tests you can use `Infinity\Bundle\TestBundle\Test\DBTestCase` that will take care of setting up and tearing down the test DB respectively before and after every single test.

## DatabaseHelper

TestBundle also provides the DatabaseHelper, which can be used to setup or drop the testing DB.  
This class is used internally to create and drop databases, but you can use it directly to perform these operations.

The following example shows how to create and drop the DB using `setUpBeforeClass` and `tearDownAfterClass`.

```php
# MyTestCase.php
use Infinity\Bundle\TestBundle\Test\Helper\DatabaseHelper;

class MyTestCase extends MinkTestCase 
{
    /**
    * @before
    */
    public static function setUpDatabase()
    {

 	  $helper = new DatabaseHelper(static::getKernel());
        $helper->setUpDatabase();
    }

    /**
    * @after
    */ 
    public static function tearDownDatabase() 
    {
        $helper = new DatabaseHelper(static::getKernel());
        $helper->tearDownDatabase();
    }
}	
```