<?php

namespace atoum\AtoumBundle\Cleaner;

use atoum\AtoumBundle\Annotation\Clean;
use atoum\AtoumBundle\Test\Controller\CleanableControllerTest;

abstract class Cleaner implements CleanerInterface
{
    public function clean(CleanableControllerTest $controllerTest, Clean $annotation)
    {
        $controllerTest->preCleaning($annotation);
        $this->doClean($annotation);
        $controllerTest->postCleaning($annotation);
    }

    abstract protected function doClean(Clean $annotation);
}