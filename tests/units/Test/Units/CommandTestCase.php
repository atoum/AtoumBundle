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
                $command->getMockController()->run = $status = uniqid(),
                $container   = new \mock\Symfony\Component\DependencyInjection\ContainerInterface(),
                $kernel      = new \mock\Symfony\Component\HttpKernel\KernelInterface(),
                $kernel->getMockController()->getBundles = array(),
                $kernel->getMockController()->getContainer = $container,
                $application = new \mock\Symfony\Bundle\FrameworkBundle\Console\Application($kernel),
                $object = new \mock\atoum\AtoumBundle\Test\Units\CommandTestCase(),
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
