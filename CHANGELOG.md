3.0.0 - 2025-10-14
=====

## Breaking Changes

* Symfony 7+ compatibility - Minimum PHP version: 8.1
* Migrated from PSR-0 to PSR-4 autoloading
* `ContainerAwareCommand` replaced with `Command` using dependency injection
* `Client` replaced with `KernelBrowser` in WebTestCase
* Commands now use constructor injection instead of container access
* `getRootDir()` replaced with `getProjectDir()`
* Return type declarations added to all methods
* Modern PHP 8+ syntax (typed properties, union types, etc.)

## Improvements

* **New:** `--directory` option for modern Symfony 7+ testing approach
  - Test any directory directly without bundle configuration
  - `bin/console atoum --directory=src/Tests`
  - `bin/console atoum --directory=tests`
  - Multiple directories supported: `--directory=tests/Unit --directory=tests/Integration`
  - Full backward compatibility with bundle-based testing
* Better type safety with full return type declarations
* Service autowiring support
* Console command attributes (`#[AsCommand]`) support
* Modernized code following Symfony 7 best practices
* PHPStan level 6 compliance (0 errors)
* PHP-CS-Fixer and Rector integration for code quality

2.0.0 - 2017-07-19
=====

## Bugfix

* [#110](https://github.com/atoum/AtoumBundle/pull/110) Rename reserved "object" to "phpObject" ([@NiniGeek])

1.6.0
=====

* [#107](https://github.com/atoum/AtoumBundle/pull/107) Add debug option ([@jdecool])

1.5.0
===========

* [#106](https://github.com/atoum/AtoumBundle/pull/106) Add debug option ([@Djuuu])
* [#105](https://github.com/atoum/AtoumBundle/pull/105) Add phpDoc on Test and WebTest classes ([@maxailloud])
* [#104](https://github.com/atoum/AtoumBundle/pull/104) Add loop mode support ([@Djuuu])
* [#103](https://github.com/atoum/AtoumBundle/pull/103) Improve compatibility with Symfony 3 ([@lolautruche])
* [#100](https://github.com/atoum/AtoumBundle/pull/100) Add option to display a light report ([@gpaton])

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
[@Djuuu]: https://github.com/Djuuu
[@lolautruche]: https://github.com/lolautruche
[@gpaton]: https://github.com/gpaton
[@maxailloud]: https://github.com/maxailloud
[@jdecool]: https://github.com/jdecool
[@NiniGeek]: https://github.com/NiniGeek
