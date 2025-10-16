<?php

namespace atoum\AtoumBundle\Scripts;

use atoum\atoum\scripts\runner as BaseRunner;

/**
 * @method bool runAgain() Check if the loop should run again (from parent class)
 */
class Runner extends BaseRunner
{
    protected function loop(): self
    {
        $php = new \atoum\atoum\php();

        foreach ($_SERVER['argv'] as $arg) {
            switch ($arg) {
                // Remove the loop option
                case '-l':
                case '--loop':
                    break;
                default:
                    $php->addOption($arg);
            }
        }

        if (true === $this->cli->isTerminal()) {
            $php->addOption('--force-terminal');
        }

        $addScoreFile = false;

        foreach ($this->argumentsParser->getValues() as $argument => $values) {
            switch ($argument) {
                case '-sf':
                case '--score-file':
                    $addScoreFile = true;

                    break;
            }
        }

        if (null === $this->scoreFile) {
            $this->scoreFile = sys_get_temp_dir().'/atoum.score';

            @unlink($this->scoreFile);

            $addScoreFile = true;
        }

        if (true === $addScoreFile) {
            $php->addOption(sprintf('--score-file=%s', $this->scoreFile));
        }

        while (true === $this->canRun()) {
            passthru((string) $php);

            if (false === $this->loop || false === $this->runAgain()) {
                $this->stopRun();
            }
        }

        return $this;
    }
}
