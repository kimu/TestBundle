# Strategy to manage your Behat and Phpspec configuration files

If you're using the post install scripts supplied by TestBundle after the installation you can find two new files under `app/config`, `behat.yml.dist` and `phpspec.yml.dist`.  
From this point you can decide to handle this file manually, but to make you life easier you should use [https://github.com/Incenteev/ParameterHandler](https://github.com/Incenteev/ParameterHandler) which is installed by default usignt the Symfony standard edition.

Add this lines to you composer.json

```json
"extra": {
        ...,
        "incenteev-parameters": [
            {
                "file": "app/config/parameters.yml",
                "dist-file": "app/config/parameters.yml.dist"
            },
            {
                "file": "behat.yml",
                "dist-file": "app/config/behat.yml.dist",
                "parameter-key": "default"
            },
            {
                "file": "phpspec.yml",
                "dist-file": "app/config/phpspec.yml.dist",
                "parameter-key": "suites"
            }
        ]
    }
```

Doing so you're instructing ParameterHandler to take care of your config files. When you need to add a new configuration to these files add them to your dist files in `app/config`. Then you can decide whether to copy the new content in your yml (under the root of the project...) files manually or to open your terminal and type 

```sh
./composer.phar run-script post-install-cmd
```
Which will do the job for you. 

Once copied you just need to change values accordingly to your local dev environment.

If you added the previous line to composer.json before creating `phpspec.yml` and `behat.yml` you will get those file created and populated for free.