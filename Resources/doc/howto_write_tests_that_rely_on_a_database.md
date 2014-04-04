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
