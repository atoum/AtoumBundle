<?php

namespace atoum\AtoumBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RunnerResolverPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $runnerId = 'atoum.runner.real';
        if ($container->hasParameter('atoum.runner.service')) {
            $runnerId = $container->getParameter('atoum.runner.service');
        }

        $container->setAlias("atoum.runner", $runnerId);

    }

}

