<?php

namespace atoum\AtoumBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * BundleDirectoriesResolverPass.
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class BundleDirectoriesResolverPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $bundles = $container->getParameter('kernel.bundles');
        $bundleContainer = $container->getDefinition('atoum.configuration.bundle.container');
        $configuration = $container->getParameterBag()->resolveValue($container->getParameter('atoum.bundles'));

        foreach ($configuration as $bundleName => $data) {
            if (!isset($bundles[$bundleName])) {
                throw new \LogicException(sprintf('Bundle "%s" does not exists.', $bundleName));
            }

            $rc = new \ReflectionClass($bundles[$bundleName]);
            $fileName = $rc->getFileName();
            if (false === $fileName) {
                throw new \LogicException(sprintf('Cannot determine file name for bundle "%s".', $bundleName));
            }
            $directory = dirname($fileName);

            $directories = array_map(
                function ($v) use ($directory) {
                    return $directory.'/'.$v;
                },
                $data['directories'],
            );

            $bundleClass = $container->getParameter('atoum.configuration.bundle.class');
            if (!is_string($bundleClass)) {
                throw new \LogicException('Parameter "atoum.configuration.bundle.class" must be a string.');
            }
            
            $definition = new Definition(
                $bundleClass,
                [$bundleName, $directories],
            );

            $bundleContainer->addMethodCall('add', [$definition]);
        }
    }
}
