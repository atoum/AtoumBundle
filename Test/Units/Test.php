<?php

namespace atoum\AtoumBundle\Test\Units;

use atoum\AtoumBundle\Test\Generator;
use mageekguy\atoum;

abstract class Test extends atoum\test
{
    /**
     * @param atoum\factory $factory factory
     */
    public function __construct(atoum\factory $factory = null)
    {
        $this->setTestNamespace('Tests\Units');
        parent::__construct($factory);
    }

    public function setAssertionManager(test\assertion\manager $assertionManager = null)
    {
        $faker = \Faker\Factory::create();

        return parent::setAssertionManager($assertionManager)
            ->getAssertionManager()
                ->setHandler('faker', function() use($faker) { return $faker; })
        ;
    }
}
