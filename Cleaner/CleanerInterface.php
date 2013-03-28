<?php

namespace atoum\AtoumBundle\Cleaner;

use atoum\AtoumBundle\Annotation\Clean;
use atoum\AtoumBundle\Test\Controller\CleanableControllerTest;

interface CleanerInterface
{
    public function clean(CleanableControllerTest $controllerTest, Clean $annotation);
}