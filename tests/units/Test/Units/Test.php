<?php

namespace atoum\AtoumBundle\tests\units\Test\Units;

use atoum\atoum;

class Test extends atoum\test
{
    public function testClass(): void
    {
        $this->testedClass->isSubclassOf('\\atoum\\atoum\\test');
    }

    public function testSetAssertionManager(): void
    {
        $this
            ->if($object = new \mock\atoum\AtoumBundle\Test\Units\Test())
            ->and($manager = new \mock\atoum\atoum\test\assertion\manager())
            ->then
                ->object($object->setAssertionManager($manager))->isIdenticalTo($object)
                ->mock($manager)
                    ->call('setHandler')->withArguments('faker')->once()
            /** @var \atoum\AtoumBundle\Test\Units\Test $object */
            ->if($object = new \mock\atoum\AtoumBundle\Test\Units\Test())
            /** @var \Faker\Generator $generator */
            ->and($this->calling($object)->getFaker = $generator = new \mock\Faker\Generator())
            ->and($this->calling($generator)->__call->doesNothing())
            ->then
                ->variable($object->faker->{$provider = uniqid()}())
                ->mock($generator)
                    ->call('__call')->withArguments($provider)->once()
        ;
    }

    public function testGetFaker()
    {
        $this
            ->if($object = new \mock\atoum\AtoumBundle\Test\Units\Test())
            ->then
                ->object($faker = $object->getFaker())->isInstanceOf('\\Faker\\Generator')
                ->object($object->getFaker())
                    ->isInstanceOf('\\Faker\\Generator')
                    ->isNotIdenticalTo($faker)
        ;
    }
}
