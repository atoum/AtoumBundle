<?php

namespace atoum\AtoumBundle\Test\Units;

use mageekguy\atoum;

abstract class Test extends atoum\test
{
    public function __construct(atoum\adapter $adapter = null, atoum\annotations\extractor $annotationExtractor = null, atoum\asserter\generator $asserterGenerator = null, atoum\test\assertion\manager $assertionManager = null, \closure $reflectionClassFactory = null)
    {
        $this->setTestNamespace('Tests\Units');

        parent::__construct($adapter, $annotationExtractor, $asserterGenerator, $assertionManager, $reflectionClassFactory);
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
}
