<?php

namespace atoum\AtoumBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration
 *
 * @uses ConfigurationInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $tb = new TreeBuilder();

        return $tb
            ->root('atoum_atoum')
                ->children()
                    ->arrayNode('bundles')
                        ->useAttributeAsKey('name')
                        ->prototype('array')
                            ->children()
                                ->arrayNode('directories')
                                    ->defaultValue(array(
                                        'Tests/Units', 'Tests/Controller',
                                    ))
                                    ->prototype('scalar')
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
