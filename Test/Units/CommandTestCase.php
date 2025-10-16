<?php

namespace atoum\AtoumBundle\Test\Units;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class CommandTestCase extends Test
{
    /**
     * Create a CommandTester instance to test commands.
     */
    public function createCommandTester(Command $command): CommandTester
    {
        // Create Kernel
        $kernel = $this->getKernel();
        $kernel->boot();

        // Create application
        $application = new Application($kernel);
        $application->add($command);

        // Get command name with fallback
        $commandName = $command->getName();
        if (null === $commandName) {
            throw new \LogicException('Command name cannot be null.');
        }

        // Create command tester for the given command
        $commandTester = new CommandTester(
            $application->find($commandName),
        );

        return $commandTester;
    }
}
