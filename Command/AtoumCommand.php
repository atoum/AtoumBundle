<?php

namespace atoum\AtoumBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use mageekguy\atoum\scripts\runner;

/**
 * AtoumCommand
 *
 * @uses ContainerAwareCommand
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class AtoumCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('atoum')
            ->setDescription('Launch atoum tests.')
            ->setHelp(<<<EOF
Launch tests of AcmeFooBundle:

<comment>./app/console atoum AcmeFooBundle</comment>

Launch tests of many bundles:

<comment>./app/console atoum AcmeFooBundle bundle_alias_extension ...</comment>
EOF
            )
            ->addArgument('bundles', InputArgument::IS_ARRAY, 'Launch tests of these bundles.')
            ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $runner = new runner('atoum');

        foreach ($input->getArgument('bundles') as $name) {
            $bundle = $this->extractBundleFromKernel($name);

            $runner->addTestAllDirectory(sprintf('%s/Tests/Units', $bundle->getPath()));
        }

        $runner->run(array(
            '--test-all'
        ));
    }

    /**
     * @param string $name name
     *
     * @return Bundle
     */
    protected function extractBundleFromKernel($name)
    {
        $bundles = $this->getContainer()->get('kernel')->getBundles();

        if (preg_match('/Bundle$/', $name)) {
            if (!isset($bundles[$name])) {
                throw new \LogicException(sprintf('Bundle "%s" does not exists or is not activated.', $name));
            }

            return $bundles[$name];
        } else {
            foreach ($bundles as $bundle) {
                $extension = $bundle->getContainerExtension();

                if ($extension && $extension->getAlias() == $name) {
                    return $bundle;
                }
            }

            throw new \LogicException(sprintf('Bundle with alias "%s" does not exists or is not activated.', $name));
        }
    }
}
