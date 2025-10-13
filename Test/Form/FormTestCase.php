<?php

namespace atoum\AtoumBundle\Test\Form;

use atoum\atoum;
use atoum\AtoumBundle\Test\Units\Test;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Forms;

abstract class FormTestCase extends Test
{
    /**
     * @var FormBuilder
     */
    protected $builder;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    protected $factory;

    public function __construct(?atoum\adapter $adapter = null, ?atoum\annotations\extractor $annotationExtractor = null, ?atoum\asserter\generator $asserterGenerator = null, ?atoum\test\assertion\manager $assertionManager = null, ?\Closure $reflectionClassFactory = null)
    {
        parent::__construct($adapter, $annotationExtractor, $asserterGenerator, $assertionManager, $reflectionClassFactory);

        $this->setTestNamespace('Tests');

        // Creates the form factory builder
        $this->factory = Forms::createFormFactoryBuilder()
            ->addExtensions($this->getExtensions())
            ->getFormFactory();

        // Mocks the event dispatcher
        // Class exists test to prevent mocking again the EventDispatcherInterface if several form type tests are set to execute together
        if (!class_exists('\mock\Symfony\Component\EventDispatcher\EventDispatcherInterface')) {
            /* @phpstan-ignore-next-line */
            $this->mockGenerator->generate('\Symfony\Component\EventDispatcher\EventDispatcherInterface');
        }
        /* @phpstan-ignore-next-line */
        $this->dispatcher = new \mock\Symfony\Component\EventDispatcher\EventDispatcherInterface();
        /* @phpstan-ignore-next-line */
        $this->builder = new FormBuilder(null, null, $this->dispatcher, $this->factory);
    }

    /**
     * @return array<mixed>
     */
    public function getExtensions(): array
    {
        return [];
    }
}
