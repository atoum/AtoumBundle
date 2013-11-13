<?php
namespace atoum\AtoumBundle\tests\units\Scripts;

require_once __DIR__ . '/../../bootstrap.php';

use mageekguy\atoum;
use mageekguy\atoum\asserter;
use atoum\AtoumBundle\Scripts\Runner as TestedClass;

class Runner extends atoum\test
{
    public function testClass()
    {
        $this
            ->testedClass
                ->isSubclassOf('mageekguy\atoum\scripts\runner')
            ->string(TestedClass::DEFAULT_ENVIRONMENT)->isEqualTo('test')
        ;
    }

    public function test__construct()
    {
        $this
            ->if($script = new TestedClass($name = uniqid()))
            ->then
                ->string($script->getEnvironment())->isEqualTo(TestedClass::DEFAULT_ENVIRONMENT)
                ->object($defaultTestFactory = $script->getTestFactory())->isIdenticalTo($script->getDefaultTestFactory())
                ->mock($defaultTestFactory('mock\atoum\AtoumBundle\Test\Units\WebTestCase'))
                    ->call('setKernelEnvironment')->withArguments(TestedClass::DEFAULT_ENVIRONMENT)->once()
        ;
    }

    public function testSetRunner()
    {
        $this
            ->if($script = new TestedClass($name = uniqid()))
            ->then
                ->object($script->setRunner())->isIdenticalTo($script)
            ->if($runner = new \mock\mageekguy\atoum\runner())
            ->then
                ->object($script->setRunner($runner))->isIdenticalTo($script)
                ->mock($runner)
                    ->call('setTestFactory')->withArguments($script->getDefaultTestFactory())->once();
        ;
    }

    public function testGetSetTestFactory()
    {
        $this
            ->given($runner = new \mock\mageekguy\atoum\runner())
            ->if($script = new TestedClass($name = uniqid()))
            ->and($script->setRunner($runner))
            ->then
                ->object($script->getTestFactory())->isIdenticalTo($script->getDefaultTestFactory())
                ->object($script->setTestFactory($factory = function() {}))->isIdenticalTo($script)
                ->object($script->getTestFactory())->isIdenticalTo($factory)
                ->mock($runner)
                    ->call('getTestFactory')->withoutAnyArgument()->twice()
                    ->call('setTestFactory')->withArguments($factory)->once()
        ;
    }

    public function testGetDefaultTestFactory()
    {
        $this
            ->if($script = new TestedClass($name = uniqid()))
            ->then
                ->object($defaultTestFactory = $script->getDefaultTestFactory())->isInstanceOf('closure')
                ->object($script->getDefaultTestFactory())->isIdenticalTo($defaultTestFactory)
        ;
    }

    public function testGetSetEnvironment()
    {
        $this
            ->if($script = new TestedClass($name = uniqid()))
            ->then
                ->string($script->getEnvironment())->isEqualTo(TestedClass::DEFAULT_ENVIRONMENT)
                ->object($script->setEnvironment($environment = uniqid()))->isIdenticalTo($script)
                ->string($script->getEnvironment())->isEqualTo($environment)
        ;
    }
}
