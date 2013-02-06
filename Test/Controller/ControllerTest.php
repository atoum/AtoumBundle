<?php

namespace atoum\AtoumBundle\Test\Controller;

use atoum\AtoumBundle\Test\Units\WebTestCase;
use mageekguy\atoum;

abstract class ControllerTest extends WebTestCase
{
    public function __construct(atoum\adapter $adapter = null, atoum\annotations\extractor $annotationExtractor = null, atoum\asserter\generator $asserterGenerator = null, atoum\test\assertion\manager $assertionManager = null, \closure $reflectionClassFactory = null)
    {
        parent::__construct($adapter, $annotationExtractor, $asserterGenerator, $assertionManager, $reflectionClassFactory);
        $this->setTestNamespace('Tests');
    }
}
