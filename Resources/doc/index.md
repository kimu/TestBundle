# Infinity Test Bundle

The aim of the Infinity Test Bundle is to provide a ready to use testing environment based on Behat, Mink, Phpspec and Selenium.

## Install the bundle
> Infinity Test Bundle requires `Xvfb` and `Selenium2` to be installed in the system before installing the bundle.
 In order to install `Selenium2` and `Xvfb` in you system run `yum install selenium` in a terminal before using this bundle.

In order to install the bundle in your project you must add a line in your composer.json

```json
{
    "require": {
        "infinitytracking/test-bundle": "*"
    }
}
```

and run

```sh
./composer.phar update infinitytracking/test-bundle
```

The Test Bundle will install Behat, Phpspec, Mink, Goutte and Selenium2 drivers and the Behat Symfony2 extension as dependencies.

### Post installation
After the installation you should copy `phpspec.yml` and `behat.yml` in the root of your project if you don't have these files already.    
You can find these files under the Test folder in the bundle root.    

These files comes with common configurations for both bundles, but you can of course change them to suit your needs.

If you haven't already, init `Behat` typing `bin/behat --init` from the root of your project. Doing so you will create a folder 
called `features` under the root of your project. That folder is where you have to save all behat files (features, contexts and bootstrap files). 

## Configuring the bundle
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
