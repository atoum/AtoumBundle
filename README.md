AtoumBundle
===========

[![Build Status](https://secure.travis-ci.org/atoum/AtoumBundle.png)](http://travis-ci.org/atoum/AtoumBundle)

This bundle provides a (very) simple integration of [atoum](https://github.com/atoum/atoum), the simple, modern and
intuitive unit testing framework for PHP, from [mageekguy](https://github.com/mageekguy) into Symfony2.

## Installation

### All with composer

```json
{
    "require": {
        "atoum/atoum-bundle": "dev-master"
    }
}
```

## Simple Usage

Make your test class extends the ```atoum\AtoumBundle\Test\Units\Test``` class of the bundle.

Don't forget to load this class with your favorite method (require, autoload, ...).

``` php
<?php

//if you don't use a bootstrap file, you need to require the autoload
require __DIR__ . '/../../../../../../../vendor/autoload.php';

// use path of the atoum.phar as bello if you don't want to use atoum via composer
//require_once __DIR__ . '/../../../../../vendor/mageekguy.atoum.phar';

use atoum\AtoumBundle\Test\Units;

class helloWorld extends Units\Test
{
}
```

## Web test case

You can easily create a kernel environment:

``` php
<?php

require __DIR__ . '/../../../../../../../vendor/autoload.php';

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

You can test your controller with the ```ControllerTest``` class (it extends WebTestCase):

``` php
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
        ;
    }
}
```
