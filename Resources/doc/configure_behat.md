# Configure Behat
This document will guide you through all required steps to configure Behat under your dev container.

## Behat.yml
Once the bundle is installed copy `behat.yml.dist` under the `app/config` folder of your project and make another copy under the root of your project and rename it as `behat.yml`. You can find `behat.yml.dist` under `TestBundle/Test`. 

In `behat.yml` you must replace the `base_url` parameter in order to match your local address.