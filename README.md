AtoumBundle
===========

[![Build Status](https://secure.travis-ci.org/atoum/AtoumBundle.png)](http://travis-ci.org/atoum/AtoumBundle)

This bundle provides a (very) simple integration of [atoum](https://github.com/atoum/atoum), the simple, modern and intuitive unit testing framework for PHP, from [mageekguy](https://github.com/mageekguy) into Symfony2.

## Installation

## 1 - With composer

```json
{
    "require": {
        "atoum/atoum-bundle": "~1.1"
    }
}
```

In most of the cases you don't need AtoumBundle in your production environment.

```json
{
    "require-dev": {
        "atoum/atoum-bundle": "~1.1"
    }
}
```

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

Make your test class extend the ```atoum\AtoumBundle\Test\Units\Test``` class of the bundle.

*Don't forget to load this class with your favorite method (require, autoload, ...) if you don't use composer.*

```php
<?php

// src/Acme/MyBundle/Tests/Units/Entity/HelloWorld.php

namespace Acme\MyBundle\Tests\Units\Entity;
// if you don't use a bootstrap file, (or composer) you need to require the application autoload
//require __DIR__ . '/../../../../../../app/autoload.php';

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

## Command test case

You can also easily test a command:

```php
<?php

namespace My\Bundle\FoobarBundle\Tests\Units\Command;

use atoum\AtoumBundle\Test\Units as AtoumBundle;
use mageekguy\atoum;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

// Assuming that this command will display "Success" if succes, and returns a boolean
use My\Bundle\FoobarBundle\Tests\Units\Command\FoobarCommand as Base;

class FoobarCommand extends AtoumBundle\CommandTestCase
{
    public function testExecute()
    {
        $this
            ->given(
                $command = new Base()
            )
            ->if($commandTester = $this->createCommandTester($command))
            ->then
                ->boolean($commandTester->execute())
                    ->isTrue()
                ->string($commandTester->getDisplay())
                    ->contains("Success")
        ;
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

## Test a form type

You can test your form types with the ```FormTestCase``` class as the [official symfony 2 documentation](http://symfony.com/doc/current/cookbook/form/unit_testing.html "How to unit test your forms") shows it.
But as the official documentation fits the PHPUnit testing framework, here comes this documentation
first example atoum-translated :

```php
<?php

namespace Acme\DemoBundle\Tests\Form;

use Acme\DemoBundle\Entity\TestEntity;
use atoum\AtoumBundle\Test\Form;
use Acme\DemoBundle\Form\TestEntityType as MyTypeToTest;

class TestEntityType extends Form\FormTestCase{

    public function testToutCourt()
    {
        $formData = array(
            'texte1' => 'test 1',
            'texte2' => 'test 2',
        );

        $type = new MyTypeToTest();
        $form = $this->factory->create($type);

        $object = new TestEntity();
        $object->fromArray($formData);

        // submit the data to the form directly
        $form->submit($formData);

        $this->boolean($form->isSynchronized())->isTrue();
        $this->variable($object)->isEqualTo($form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->array($formData)->hasKey($key);
        }
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
