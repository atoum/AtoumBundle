<?php
namespace atoum\AtoumBundle\Scripts;

use atoum\AtoumBundle;
use atoum\AtoumBundle\Test\Units\WebTestCase;
use mageekguy\atoum;
use mageekguy\atoum\exceptions;
use mageekguy\atoum\script;

/**
 * Runner
 *
 * @author Julien BIANCHI <contact@jubianchi.fr>
 */
class Runner extends atoum\scripts\runner
{
    const DEFAULT_ENVIRONMENT = 'test';

    /** @var \Closure */
    protected $defaultTestFactory;

    /** @var string */
    protected $environment;

    /**
     * @param atoum\runner $runner
     *
     * @return $this
     */
    public function setRunner(atoum\runner $runner = null)
    {
        return parent::setRunner($runner)->setTestFactory();
    }

    /**
     * @param script\arguments\parser $parser
     *
     * @return $this
     */
    public function setArgumentsParser(script\arguments\parser $parser = null)
    {
        if (null === $parser)
        {
            $superglobals = new atoum\superglobals();
            $superglobals->_SERVER['argv'] = array();
            $superglobals->_SERVER['argc'] = 0;
            $this->setArgumentsParser(new atoum\script\arguments\parser($superglobals));
        }

        return parent::setArgumentsParser($parser);
    }

    /**
     * @param \Closure $factory
     *
     * @return $this
     */
    public function setTestFactory(\Closure $factory = null)
    {
        $this->runner->setTestFactory($factory ?: $this->getDefaultTestFactory());

        return $this;
    }

    public function getTestFactory()
    {
        return $this->runner->getTestFactory();
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment ?: self::DEFAULT_ENVIRONMENT;
    }

    /**
     * @param string $environment
     *
     * @return $this
     */
    public function setEnvironment($environment = null)
    {
        $this->environment = $environment;

        return $this;
    }

    public function getDefaultTestFactory()
    {
        if (null === $this->defaultTestFactory)
        {
            $self = $this;

            $this->defaultTestFactory = function($testClass) use ($self) {
                $test = new $testClass();

                if ($test instanceof WebTestCase) {
                    $test->setKernelEnvironment($self->getEnvironment());
                }

                return $test;
            };
        }

        return $this->defaultTestFactory;
    }
} 