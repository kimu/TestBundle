# Howto Create Phpspec Tests

This document is about how to create phpspec files in the proper way, more than a general description of how create phpspec files.
For that please refer to the documentation on [http://www.phpspec.net/](http://www.phpspec.net/).

## Paths vs Namespaces
Usually when creating phpspec files you could be tempted to do something like

```sh
phpspec desc path/to/my/file
```

Well, don't do it, because the result of this command is to create a file under the full path you have just typed in under the `spec` folder.   
If you had a file named AcmeBundle/Controller/MyController.php and the AcmeBundle was under the src/ folder, the spec file would be in the following position  spec/src/AcmeBundle/Controller/MyControllerSpec.php.

What you don't want is the src/ folder, because it's not part of the implementation of your class, it's just the location where you have chosen to store all your bundles. It's a detail that doesn't need to be part of the path of the spec files, because it could change in the future, resulting in your paths under the spec folder to be all wrong.

Use the namespace instead, as it will never change (unless you change it, but in that case you have to change the location of the spec file as well and affects just one file per time, not all your files under the spec/ folder).   
Considering the same controller class mentioned before, this is the right command you have to type in your terminal.

```sh
phpspec desc full/namespace/of/my/class.
```

The only difference from a normal namespace, as you can notice, is that you have to convert all slashes in backslashes.
From `full\namespace\of\my\class` to `full/namespace/of/my/class`.

The location of the spec file now will be spec/AcmeBundle/Controller/MyControllerSpec.php, that will always be correct wherever you choose to keep you bundles in your project folder structure.

## Keep your spec files within your bundle
The previous example shows how to create specs files that are all stored in a single folder under your project root, usually the spec/ folder. However, this is not the recommended way to create and store your spec files.
Your spec files should be kept within your bundles, making easier to move your bundles and keep all related files with it.

There are a couple of ways to create your spec files under your bundle root.  
The first one is to run phpspec from the root of your bundle, like so

```sh
/home/sites/yourproject/bin/phpspec desc full/namespace/to/your/class
```

Launching phpspec from the root of your project the spec/ folder will be created inside your bundle and not under the root of your project.  
Now, in order to run your spec file you could be tempted to write this in your terminal

```sh
/home/sites/yourproject/bin/phpspec run full/namespace/to/your/class
```

But this will simply do nothing. Phpspec is not able to find your spec file in that way.  
What you have to write in order to run your spec files is

```sh
/home/sites/yourproject/bin/phpspec run relative/path/to/your/file
```

Where `relative/path/to/your/file` is the the path starting from the root of your bundle.

You can't to do the same from the root of your project, so

```sh
bin/phpspec desc relative/path/to/your/file/starting/from/the/root/of/your/project
```

Simply doesn't work.

However, there is a problem with this approach. Your spec files will run only when explicitely called from the bundle itself, bundle by bundle, and this is not what you want. You want to be able to type `bin/phpspec run` from the root of your project and see all your spec files, wherever they are, in the test results.

Luckily, there is a simple way to have this working, phpspec.yml.

### phpspec.yml
The second method to create spec files within your bundle is to use phpspec.yml to instruct phpspec on how to manage the namespace of your bundle. The advantange of this solution is that you will be also able to run all your tests justs typing `bin/phpspec run`.

For a quick introduction please read [http://www.phpspec.net/cookbook/configuration.html](http://www.phpspec.net/cookbook/configuration.html).

Unfortunately, the documentation isn't very clear or exhaustive, so here following some tips.

#### Configure a bundle under the src/ folder
In order to add a bundle that resides in the src/ folder of your project, use something somilar to this

```yaml
suites:
    your_bundle_name_suite:
        namespace: Your\Bundle\Namespace
        spec_path: src/path/to/your/bundle
        src_path: src/path/to/your/bundle
```

With this configuration you can type `bin/phpspec desc  namespace/of/your/class` from the root of your project and specs files will be created in the spec folder under the root of your bundle.  
In order to run your tests is now enough to type `bin/phpspec run` from the root of your project.

The name of the suite `your_bundle_name_suite` should be composed by the name of your bundle followed by `_suite`. The suite name for the InfinityUserBundle should be infinity_user_bundle_suite.

* `namespace`  is the namespace of your bundle.
* `spec_path` is the path where you want the spec/ folder to be created. All specs files will be stored inside this folder.
* `src_path` is the path where phpspec will create your classes.
* `spec_prefix` (not present in the example) is the name of the spec folder. It's `spec` by default, but you can choose another name. In that case add the key to the configuration of your suite. For example, you might want to save your spec files under `tests/spec`.

Every time you create a bundle, you should add a new suite to phpspec.yml.

#### Configure a bundle under the vendor/ folder
You can use suites also for creating and running spec files in shared bundle that are stored under the `vendor` folder in your project. In that case the configuration should be something like

```yaml
my_bundle_name_suite:
    namespace: My\Bundle\Namespace
    src_path: vendor/infinitytracking/my-bundle-target-dir/My/Bundle/Namespace
    spec_path: vendor/infinitytracking/my-bundle-target-dir/My/Bundle/Namespace
```
