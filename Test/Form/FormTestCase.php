<?php

namespace atoum\AtoumBundle\Test\Form;

use mageekguy\atoum;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Forms;

abstract class FormTestCase extends atoum\test
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

    public function __construct(atoum\adapter $adapter = null, atoum\annotations\extractor $annotationExtractor = null, atoum\asserter\generator $asserterGenerator = null, atoum\test\assertion\manager $assertionManager = null, \closure $reflectionClassFactory = null)
    {
        $this->setTestNamespace('Tests');

        parent::__construct($adapter, $annotationExtractor, $asserterGenerator, $assertionManager, $reflectionClassFactory);

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

    public function setAssertionManager(atoum\test\assertion\manager $assertionManager = null)
    {
        $self = $this;

        $returnFaker = function($locale = 'en_US') use ($self) {
            return $self->getFaker($locale);
        };

        parent::setAssertionManager($assertionManager)
            ->getAssertionManager()
            ->setHandler('faker', $returnFaker)
        ;

        return $this;
    }

    public function getFaker($locale = 'en_US')
    {
        return \Faker\Factory::create($locale);
    }

    public function getExtensions()
    {
        return array();
    }

}
