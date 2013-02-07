* master (next 1.1.0)

  * Add fluent interface for controllers testing
  * Add support for Faker (https://github.com/fzaninotto/Faker)
  * Compatibility break
      * static $kernel variable become a class variable
      * AtoumBundle\Test\Units\Test::getRandomString() and AtoumBundle\Test\Generator\String were removed

* 1.0.0 (2012)

  * Move the bundle to atoum vendor namespace
  * Add ControllerTest class