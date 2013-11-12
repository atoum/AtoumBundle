<?php
namespace atoum\AtoumBundle\tests\units\Test\Units;

use mageekguy\atoum;

class Test extends atoum\test
{
    public function testClass()
    {
        $this->testedClass->isSubclassOf('\\mageekguy\\atoum\\test');
    }

    public function testSetAssertionManager()
    {
        $this
            ->if($object = new \mock\atoum\AtoumBundle\Test\Units\Test())
            ->and($manager = new \mock\mageekguy\atoum\test\assertion\manager())
            ->then
                ->object($object->setAssertionManager($manager))->isIdenticalTo($object)
                ->mock($manager)
                    ->call('setHandler')->withArguments('faker')->once()
            ->if($object = new \mock\atoum\AtoumBundle\Test\Units\Test())
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
