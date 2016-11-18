<?php

namespace atoum\AtoumBundle\tests\units\Test\Units;

use mageekguy\atoum;

class CommandTestCase extends atoum\test
{
    public function testClass()
    {
        $this->testedClass->isSubclassOf('\\atoum\\AtoumBundle\\Test\\Units\\Test');
    }

    public function testCreateCommandTester()
    {
        $this
            ->given(
                $command = new \mock\Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand($commandName = uniqid()),
                $kernel      = new \mock\Symfony\Component\HttpKernel\KernelInterface(),
                $container   = new \mock\Symfony\Component\DependencyInjection\ContainerInterface(),
                $application = new \mock\Symfony\Bundle\FrameworkBundle\Console\Application($kernel),
                $object = new \mock\atoum\AtoumBundle\Test\Units\CommandTestCase(),
                $command->getMockController()->run = $status = uniqid(),
                $kernel->getMockController()->getBundles = array(),
                $kernel->getMockController()->getContainer = $container,
                $object->getMockController()->getKernel = $kernel
            )
            ->if($commandTester = $object->createCommandTester($command))
            ->then
                ->object($commandTester)
                    ->isInstanceOf('\\Symfony\\Component\\Console\\Tester\\CommandTester')
                ->variable($commandTester->execute(array()))
                    ->isEqualTo($status)
        ;
    }
}
