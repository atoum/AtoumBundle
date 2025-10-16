<?php

namespace atoum\AtoumBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * AtoumExtension.
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class AtoumExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/services'));
        $loader->load('configuration.xml');

        $container->setParameter('atoum.bundles', $config['bundles']);
    }

    public function getAlias(): string
    {
        return 'atoum';
    }
}
