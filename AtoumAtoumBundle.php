<?php

namespace atoum\AtoumBundle;

use atoum\AtoumBundle\DependencyInjection\AtoumExtension;
use atoum\AtoumBundle\DependencyInjection\Compiler\BundleDirectoriesResolverPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * AtoumAtoumBundle.
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class AtoumAtoumBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new BundleDirectoriesResolverPass());
    }

    public function getContainerExtension(): AtoumExtension
    {
        if (null === $this->extension) {
            $this->extension = new AtoumExtension();
        }

        return $this->extension;
    }
}
