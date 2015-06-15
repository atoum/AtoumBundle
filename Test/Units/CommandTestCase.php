<?php

namespace atoum\AtoumBundle\Test\Units;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class CommandTestCase extends Test
{
    /**
     * Create a CommandTester instance to test commands
     *
     * @param ContainerAwareCommand $command
     *
     * @return CommandTester
     */
    public function createCommandTester(ContainerAwareCommand $command)
    {
        // Create Kernel
        $kernel = $this->getKernel();
        $kernel->boot();

        // Create application
        $application = new Application($kernel);
        $application->add($command);

        // Create command tester for the given command
        $commandTester = new CommandTester(
            $application->find($command->getName())
        );

        return $commandTester;
    }
}

