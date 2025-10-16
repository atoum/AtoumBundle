<?php

namespace atoum\AtoumBundle\tests\units\Test\Units;

use atoum\atoum;

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
                $command = new \mock\Symfony\Component\Console\Command\Command($commandName = uniqid()),
                $command->getMockController()->run = $status = 0,
                $container = new \mock\Symfony\Component\DependencyInjection\ContainerInterface(),
                $container->getMockController()->has = false,
                $container->getMockController()->hasParameter = false,
                $container->getMockController()->get = null,
                $container->getMockController()->has = false,
                $container->getMockController()->hasParameter = false,
                $container->getMockController()->get = null,
                $kernel = new \mock\Symfony\Component\HttpKernel\KernelInterface(),
                $kernel->getMockController()->getBundles = [],
                $kernel->getMockController()->getContainer = $container,
                $kernel->getMockController()->getEnvironment = 'test',
                $kernel->getMockController()->isDebug = true,
                $application = new \mock\Symfony\Bundle\FrameworkBundle\Console\Application($kernel),
                $object = new \mock\atoum\AtoumBundle\Test\Units\CommandTestCase(),
                $object->getMockController()->getKernel = $kernel,
            )
            ->if($commandTester = $object->createCommandTester($command))
            ->then
                ->object($commandTester)
                    ->isInstanceOf('\\Symfony\\Component\\Console\\Tester\\CommandTester')
                ->integer($commandTester->execute([]))
                    ->isEqualTo($status)
        ;
    }
}
