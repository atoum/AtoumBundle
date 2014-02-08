<?php

namespace atoum\AtoumBundle\Test\Units;

use Faker;
use mageekguy\atoum;

abstract class Test extends atoum\test
{
    /**
     * {@inheritdoc}
     */
    public function __construct(atoum\adapter $adapter = null, atoum\annotations\extractor $annotationExtractor = null, atoum\asserter\generator $asserterGenerator = null, atoum\test\assertion\manager $assertionManager = null, \closure $reflectionClassFactory = null)
    {
        $this->setTestNamespace('Tests\Units');

        parent::__construct($adapter, $annotationExtractor, $asserterGenerator, $assertionManager, $reflectionClassFactory);
    }

    /**
     * @param atoum\test\assertion\manager $assertionManager
     *
     * @return $this
     */
    public function setAssertionManager(atoum\test\assertion\manager $assertionManager = null)
    {
        $self = $this;

        $returnFaker = function ($locale = 'en_US') use ($self) {
            return $self->getFaker($locale);
        };

        parent::setAssertionManager($assertionManager)
            ->getAssertionManager()
                ->setHandler('faker', $returnFaker)
        ;

        return $this;
    }

    /**
     * @param string $locale
     *
     * @return Faker\Generator
     */
    public function getFaker($locale = 'en_US')
    {
        return Faker\Factory::create($locale);
    }
}
