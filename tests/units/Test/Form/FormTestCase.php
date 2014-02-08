<?php
namespace atoum\AtoumBundle\tests\units\Test\Form;

require_once __DIR__ . '/../../../bootstrap.php';

use mageekguy\atoum;
use mageekguy\atoum\asserter;

class FormTestCase extends atoum\test
{
    public function testClass()
    {
        $this->testedClass->isSubclassOf('atoum\AtoumBundle\Test\Units\Test');
    }

    public function testConstruct()
    {
        $this
            ->object(new \mock\atoum\AtoumBundle\Test\Form\FormTestCase())
                ->isInstanceOf('atoum\AtoumBundle\Test\Form\FormTestCase');
    }
}
