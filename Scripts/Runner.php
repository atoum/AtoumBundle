<?php

namespace atoum\AtoumBundle\Scripts;

use \mageekguy\atoum\scripts\runner as BaseRunner;
use mageekguy\atoum\cli;
use mageekguy\atoum\php;

class Runner extends BaseRunner
{

    protected function loop()
    {
        $php = new php();

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

        if ($this->cli->isTerminal() === true) {
            $php->addOption('--force-terminal');
        }

        while ($this->canRun() === true) {
            passthru((string) $php);

            if ($this->loop === false || $this->runAgain() === false) {
                $this->stopRun();
            }
        }

        return $this;
    }

}
