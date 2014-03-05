# Infinity Test Bundle

The aim of the Infinity Test Bundle is to provide a ready to use testing environment based on Behat, Mink, Phpspec and Selenium.

## Activating and configuring the bundle

Once installed the Infinity Test Bundle can be activated adding the following lines to your config.yml file.

```yaml
infinity_test:
    enabled: true
```

### Configuring services substitutions

The Infinity Test Bundle allows to replace services in the test environment. This can be very useful to replace elements that cannot be
mocked and cannot be used directly as they are.
The bundle allows to replace them with classes that emulates the original class without the dependencies that make it
impossible to use it in your tests.

The `substitutions` key in the configuration of the bundle is used to list all service to replace.

```yaml
infinity_test:
    ...
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
