# Configure Behat
This document will guide you through all required steps to configure Behat under your dev container.

## Behat.yml
Initially, all you have to do is to copy `behat.yml` under the root of your project. You can find `behat.yml` under `TestBundle/Test`.  You don't need to change anything in this file.

## Base Url
The Behat Mink Extension has a `base_url` parameter that needs to be set in order to run functional or web acceptance tests under your local dev container.  
The `base_url` parameter is provided through ENV variable to the configuration and it's set up for you atuomatically when you run your tests usign `run_test.sh`.   

If you need to run Behat tests in isolation than you need to provide the `base_url` parameters setting the `BEHAT_PARAMS` ENV variable in your shell.  

You ca do it by typing 

```sh
export BEHAT_PARAMS=extensions[Behat\\MinkExtension\\Extension][base_url]=http://ict-portal2.${HOSTNAME}	
```


directly in your shell or by adding the same instruction in your `.bash_profile`.