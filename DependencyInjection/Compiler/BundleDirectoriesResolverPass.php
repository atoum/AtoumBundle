<?php

namespace atoum\AtoumBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;

/**
 * BundleDirectoriesResolverPass
 *
 * @uses CompilerPassInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class BundleDirectoriesResolverPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @throws \LogicException
     */
    public function process(ContainerBuilder $container)
    {
        $bundles         = $container->getParameter('kernel.bundles');
        $bundleContainer = $container->getDefinition('atoum.configuration.bundle.container');
        $configuration   = $container->getParameterBag()->resolveValue($container->getParameter('atoum.bundles'));

        foreach ($configuration as $bundleName => $data) {
            if ($data['is_bundle'] && !isset($bundles[$bundleName])) {
                throw new \LogicException(sprintf('Bundle "%s" does not exists.', $bundleName));
            }

            $rc        = new \ReflectionClass($data['is_bundle'] ? $bundles[$bundleName] : 'App\Kernel');
            $directory = dirname($rc->getFileName());

            $directories = array_map(
                function ($v) use ($directory) {
                    return $directory.'/'.$v;
                },
                $data['directories']
            );

            $definition = new Definition(
                $container->getParameter('atoum.configuration.bundle.class'),
                array($bundleName, $directories)
            );

            $bundleContainer->addMethodCall('add', array($definition));
        }
    }
}
