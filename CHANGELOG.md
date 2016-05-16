1.4.1
=====

## Bugfix

* [#98](https://github.com/atoum/AtoumBundle/pull/98) Fix atoum command exit codes ([@jubianchi])

1.4.0
=====

* Symfony3 compatibility
* Minimum version of Symfony : 2.3
* Minimum version of atoum : 2.1

1.3.0
=====

* Add xunit and clover report file options

1.2.1
=====

* 1.2.X depends on atoum < 2.4

1.2.0
=====

* Adds the ability to test Symfony commands (see `atoum\AtoumBundle\Test\Units\CommandTestCase`)

1.1.0
=====

  * Add command to launch tests on bundles.
  * Add fluent interface for controllers testing
  * Add support for Faker (https://github.com/fzaninotto/Faker)
  * Compatibility break
      * static $kernel variable become a class variable
      * AtoumBundle\Test\Units\Test::getRandomString() and AtoumBundle\Test\Generator\String were removed
  * Add two annotations to enable/disable kernel reset in tests : @resetKernel and @noResetKernel
  * Compatibility improvement with symfony/dom-crawler 2.3 and 2.4

1.0.0 (2012)
============

  * Move the bundle to atoum vendor namespace
  * Add ControllerTest class

[@jubianchi]: https://github.com/jubianchi
