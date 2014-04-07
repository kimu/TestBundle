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

If you need a DB just add the tag `@db` to you scenarios (works only for scenarios). TestBundle will take care of creating a DB before running the scenario and eventually drop it after the scenario has run.

You can use what you find more comfortable to you to create data in DB. You can access Doctrine implementing the `KernelAwareInterface` in you Context class.

For massive data creation take a look to [https://github.com/fzaninotto/Faker](https://github.com/fzaninotto/Faker).

## PHPUnit and DB

If you need a DB to be set up for you in your PHPUnit tests you can use `Infinity\Bundle\TestBundle\Test\DBTestCase` that will take care of setting up and tearing down the test DB respectively before and after every single test.

## DatabaseHelper

TestBundle provides also an helper, the DatabaseHelper which can be used to setup or drop the testing DB. This class is used internally to create and drop databases, but you can use it directly to perform these operations.

The following example shows how to create and drop the DB using `setUpBeforeClass` and `tearDownAfterClass`.

```php
# MyTestCase.php
use Infinity\Bundle\TestBundle\Test\Helper\DatabaseHelper;

public static function setUpBeforeClass()
{
    // Error suppresion is used to suppress the warning thrown because getKernel is not a static function 
    $helper = new DatabaseHelper(@self::getKernel());
    $helper->setUpDatabase();
    parent::setUpBeforeClass();}

public static function tearDownAfterClass() 
{
    $helper = new DatabaseHelper(@self::getKernel());
    $helper->tearDownDatabase();
    parent::tearDownAfterClass();		}
	
```