# Infinity Test Bundle

The aim of the Infinity Test Bundle is to provide a ready to use testing environment based on Behat, Mink, Phpspec and Selenium.

## Install the bundle
<hr />
 Infinity Test Bundle requires `Xvfb` and `Selenium2` to be installed in the system before installing the bundle.
 In order to install `Selenium2` and `Xvfb` in you system run 
 
```sh
yum install selenium
```

 in a terminal before using this bundle.
 <hr />

In order to install the bundle in your project you must add a few lines in your composer.json

```json
{
    "require": {
        "infinitytracking/test-bundle": "*"
    }
    ...
    "scripts": {
        "post-install-cmd": [
      		"Infinity\\Bundle\\TestBundle\\Composer\\ScriptHandler::installConfigurationFiles",
      		...
      		"Infinity\\Bundle\\TestBundle\\Composer\\ScriptHandler::initBehat"
        ],
        "post-update-cmd": [
      		"Infinity\\Bundle\\TestBundle\\Composer\\ScriptHandler::installConfigurationFiles",
      		...
      		"Infinity\\Bundle\\TestBundle\\Composer\\ScriptHandler::initBehat"
        ]
    },
}
```

The first post install script installs behat.yml.dist and phpspec.yml.dist under your `app/config` folder. Take a look to this document [Strategy to manage behat and phpspec yml config files](howto_manage_behat_and_phpspec_config.md) to see how you can manage these file.  
The second post install script initialises Behat for you and remove FeatureContext.php because Behat is configured to use Infinity\Bundle\TestBundle\Test\Context\FeatureContext class as default main context.


The order of the two scripts is important. The first script must be the first script in the list and must be before the Incenteev\ParameterHandler ScriptHandler, if you're using it as suggested in [Strategy to manage behat and phpspec yml config files](howto_manage_behat_and_phpspec_config.md).  
The second script must be the last script in the list.

Finally, install the bundle typing this in a terminal

```sh
./composer.phar update infinitytracking/test-bundle
```

The Test Bundle will install Behat, Phpspec, Mink, Goutte and Selenium2 drivers and the Behat Symfony2 extension as dependencies.

## Configuring the bundle
### Configuring behat/mink-bundle

The Test bundle install also [behat/mink-bundle](https://github.com/Behat/MinkBundle) which is used to run web acceptance tests and functional tests.   
Mink-bundle have is own configuration that must be set up in order to use the bundle. Please follow the documentation at [https://github.com/Behat/MinkBundle/blob/master/Resources/doc/index.rst#bundle-installation--setup]
(https://github.com/Behat/MinkBundle/blob/master/Resources/doc/index.rst#bundle-installation--setup)

### Configuring Behat

Read the documentation at [Configure Behat](configure_behat.md)

### Configuring services substitutions

The Infinity Test Bundle allows to replace services in the test environment. This can be very useful to replace elements that cannot be
mocked and cannot be used directly as they are.
The bundle allows to replace them with classes that emulates the original class without the dependencies that make it
impossible to use it in your tests.

The `substitutions` key in the configuration of the bundle is used to list all service to replace.

```yaml
infinity_test:
    substitutions:
        servicename: { class: 'namespace\of\the\class', inherit_arguments: true }
```

Every substitutions is composed with `servicename` the name of the service to replace, `class` the name of the new class to use.
`inherit_arguments` is an optional parameter that can be omitted (default to true) which indicates if the arguments passed to the
original service should be passed to the new class. If you don't need them, just set it to false.

If you don't need to change the value of `inherit_arguments` you can define substitutions using only the name of the class

```yaml
    substitutions:
        servicename: 'namespace\of\the\class'
```

## Utilities
The Infinity Test Bundle installs 3 files under the bin folder of your project.    
Files are:

* `start_selenium.sh` which starts Xvfb and Selenium2
* `stop_selenium.sh` which stops Xvfb and Selenium2
* `run_test.sh` which start and stop Xvfb and Selenium and run all Behat and Phpspec test in your project.

It's important that you launch `run_test.sh` using `. bin/run_test.sh` or `source bin/run_test.sh` otherwise part of what the script does won't be correctly executed.

<hr />
# Further readings
[Howto create phpsepc tests](howto_create_phpspec_tests.md)   
[Configure Behat](configure_behat.md)