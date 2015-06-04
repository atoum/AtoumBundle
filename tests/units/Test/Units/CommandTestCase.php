<?php

namespace atoum\AtoumBundle\tests\units\Test\Units;

require_once __DIR__ . '/../../../bootstrap.php';

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
                $command->getMockController()->run = $status = uniqid()
            )
            ->given(
                $kernel      = new \mock\Symfony\Component\HttpKernel\KernelInterface(),
                $application = new \mock\Symfony\Bundle\FrameworkBundle\Console\Application($kernel)
            )
            ->given(
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

