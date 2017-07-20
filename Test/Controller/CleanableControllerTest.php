<?php

namespace atoum\AtoumBundle\Test\Controller;

abstract class CleanableControllerTest extends ControllerTest
{
    public function beforeTestMethod($method)
    {
        parent::beforeTestMethod($method);

        if (null === $this->kernel) {
            $this->kernel = static::createKernel();
            $this->kernel->boot();
        }

        $container = $this->kernel->getContainer();

        $cleanerManager = $container->get('atoum.manager.cleaning');
        $cleanerManager->process($this, $method);
    }
}
