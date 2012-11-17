<?php

namespace atoum\AtoumBundle\Tests\Controller;

use atoum\AtoumBundle\Tests\Units\WebTestCase;
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
