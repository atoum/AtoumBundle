AtoumBundle
===========

[![Build Status](https://secure.travis-ci.org/atoum/AtoumBundle.png)](http://travis-ci.org/atoum/AtoumBundle)

This bundle provides a (very) simple integration of [atoum](https://github.com/atoum/atoum), the simple, modern and intuitive unit testing framework for PHP, from [mageekguy](https://github.com/mageekguy) into Symfony2.

## Installation

## 1 - With composer

```json
{
    "require": {
        "atoum/atoum": "dev-master",
        "atoum/atoum-bundle": "dev-master"
    }
}
```

In most of the cases you don't need AtoumBundle in your production environment.

```json
{
    "require-dev": {
        "atoum/atoum": "dev-master",
        "atoum/atoum-bundle": "dev-master"
    }
}
```

Don't forget to add Atoum, too!

## 2 - Command

AtoumBundle is provided with a Symfony command. You can launch atoum tests on specific bundles.

### 2-a Registering in the kernel

You have to define AtoumBundle on `AppKernel`

```php
if (in_array($this->getEnvironment(), array('dev', 'test'))) {
    //.....
    $bundles[] = new atoum\AtoumBundle\AtoumAtoumBundle();
}
```

### 2-b Configuration

Define your bundles on configuration (if you want to use it only in test environment, in `config_test.yml` only):

```yaml
atoum:
    bundles:
        # note that the full name, including vendor, is required
        AcmeFooBundle: ~ # FooBundle is defined with directories Tests/Units, Tests/Controller
        MeBarBundle:
            directories: [Tests/Units, Tests/Functional, ...]
```

### 2-c Command-line usage

Then you can use:

```shell
$ php app/console atoum FooBundle --env=test # launch tests of FooBundle
$ php app/console atoum FooBundle BarBundle --env=test # launch tests of FooBundle and BarBundle
$ php app/console atoum acme_foo --env=test # launch tests of bundle where alias is acme_foo
$ php app/console atoum --env=test # launch tests from configuration.
```

## Simple Usage

Make your test class extends the ```atoum\AtoumBundle\Test\Units\Test``` class of the bundle.

*Don't forget to load this class with your favorite method (require, autoload, ...) if you don't use composer.*

```php
<?php

// if you don't use a bootstrap file, (or composer) you need to require the application autoload
//require __DIR__ . '/../../../../../../../app/autoload.php';

// use path of the atoum.phar as bellow if you don't want to use atoum via composer
//require_once __DIR__ . '/../../../../../vendor/mageekguy.atoum.phar';

use atoum\AtoumBundle\Test\Units;

class helloWorld extends Units\Test
{
}
```

## Web test case

You can easily create a kernel environment:

```php
<?php

require __DIR__ . '/../../../../../../../app/autoload.php';

use atoum\AtoumBundle\Test\Units;

class helloWorld extends Units\WebTestCase
{
    public function testMyTralala()
    {
        $client = $this->createClient();
    }
}
```

### Known issues

- The path of the AppKernel cannot be found, override the method ```getKernelDirectory```
and add the path to your ```app``` directory.

## Test a controller

You can test your controller with the ```ControllerTest``` class (it extends `WebTestCase` - each file must correspond to a Symfony2 controller):

```php
<?php

namespace vendor\FooBundle\Tests\Controller;

use atoum\AtoumBundle\Test\Units\WebTestCase;
use atoum\AtoumBundle\Test\Controller\ControllerTest;

class BarController extends ControllerTest
{
    public function testGet()
    {
        $this
            ->request(array('debug' => true))
                ->GET('/demo/' . uniqid())
                    ->hasStatus(404)
                    ->hasCharset('UTF-8')
                    ->hasVersion('1.1')
                ->POST('/demo/contact')
                    ->hasStatus(200)
                    ->hasHeader('Content-Type', 'text/html; charset=UTF-8')
                    ->crawler
                        ->hasElement('#contact_form')
                            ->hasChild('input')->exactly(3)->end()
                            ->hasChild('input')
                                ->withAttribute('type', 'email')
                                ->withAttribute('name', 'contact[email]')
                            ->end()
                            ->hasChild('input[type=submit]')
                                ->withAttribute('value', 'Send')
                            ->end()
                            ->hasChild('textarea')->end()
                        ->end()
                        ->hasElement('li')
                            ->withContent('The CSRF token is invalid. Please try to resubmit the form.')
                            ->exactly(1)
                        ->end()
                        ->hasElement('title')
                            ->hasNoChild()
                        ->end()
                        ->hasElement('meta')
                            ->hasNoContent()
                        ->end()
                        ->hasElement('link')
                            ->isEmpty()
                        ->end()
        ;
    }
}
```

## Faker data

AtoumBundle integrates with [Faker](https://github.com/fzaninotto/Faker) library.

In your tests classes, you have access to a ```Faker\Generator``` instance with the ```faker``` asserter.

```php
public function testMyAmazingFeature()
{
    //.....
    $randomName = $this->faker->name;

    $dateTimeBetweenYesterdayAndNow = $this->faker->dateTimeBetween('-1 day', 'now');
    //.....
}
```

See [Faker's documentation](https://github.com/fzaninotto/Faker#basic-usage) about its usage.
