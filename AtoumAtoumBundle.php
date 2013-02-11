<?php

namespace atoum\AtoumBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use atoum\AtoumBundle\DependencyInjection\AtoumAtoumExtension;
use atoum\AtoumBundle\DependencyInjection\Compiler\BundleDirectoriesResolverPass;

/**
 * AtoumAtoumBundle
 *
 * @uses Bundle
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class AtoumAtoumBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new BundleDirectoriesResolverPass());
    }

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new AtoumAtoumExtension;
        }

        return $this->extension;
    }
}
