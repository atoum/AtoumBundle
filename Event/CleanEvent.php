<?php

namespace atoum\AtoumBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use atoum\AtoumBundle\Test\Controller\CleanableControllerTest;
use atoum\AtoumBundle\Annotation\Clean;

class CleanEvent extends Event
{
    protected $testController;

    protected $annotation;

    public function __construct(CleanableControllerTest $testController, Clean $annotation)
    {
        $this->testController = $testController;
        $this->annotation = $annotation;
    }

    public function getTestController()
    {
        return $this->testController;
    }

    public function getAnnotation()
    {
        return $this->annotation;
    }
}