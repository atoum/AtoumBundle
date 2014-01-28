<?php

namespace atoum\AtoumBundle\Test\Form;

use atoum\AtoumBundle\Test\Units\Test;
use mageekguy\atoum;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Forms;

abstract class FormTestCase extends Test
{

    /**
     * @var \Symfony\Component\Form\FormBuilder
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

    /**
     * {@inheritdoc}
     */
    public function __construct(atoum\adapter $adapter = null, atoum\annotations\extractor $annotationExtractor = null, atoum\asserter\generator $asserterGenerator = null, atoum\test\assertion\manager $assertionManager = null, \closure $reflectionClassFactory = null)
    {
        parent::__construct($adapter, $annotationExtractor, $asserterGenerator, $assertionManager, $reflectionClassFactory);

        $this->setTestNamespace('Tests');

        // Creates the form factory builder
        $this->factory = Forms::createFormFactoryBuilder()
            ->addExtensions($this->getExtensions())
            ->getFormFactory();

        // Mocks the event dispatcher
        // Class exists test to prevent mocking again the EventDispatcherInterface if several form type tests are set to execute together
        if (!class_exists('\mock\Symfony\Component\EventDispatcher\EventDispatcherInterface'))
            $this->mockGenerator->generate('\Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->dispatcher = new \mock\Symfony\Component\EventDispatcher\EventDispatcherInterface;
        $this->builder = new FormBuilder(null, null, $this->dispatcher, $this->factory);
    }

    /**
     * @return array
     */
    public function getExtensions()
    {
        return array();
    }
}
