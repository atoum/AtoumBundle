<?php

namespace atoum\AtoumBundle\Test\Controller;

use atoum\AtoumBundle\Test\Units\WebTestCase;
use mageekguy\atoum;

abstract class ControllerTest extends WebTestCase
{
    /**
     * @param atoum\factory $factory factory
     */
    public function __construct(atoum\factory $factory = null)
    {
        parent::__construct($factory);
        $this->setTestNamespace('Tests');
    }
}
