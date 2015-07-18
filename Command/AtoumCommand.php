<?php

namespace atoum\AtoumBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use atoum\AtoumBundle\Configuration\Bundle as BundleConfiguration;
use atoum\AtoumBundle\Scripts\Runner;

/**
 * AtoumCommand
 *
 * @uses ContainerAwareCommand
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class AtoumCommand extends ContainerAwareCommand
{

    /**
     * @var array List of atoum CLI runner arguments
     */
    private $atoumArguments = array();

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

Launch tests of all bundles defined on configuration:

<comment>./app/console atoum</comment>

EOF
                )
                ->addArgument('bundles', InputArgument::IS_ARRAY, 'Launch tests of these bundles.')
                ->addOption('bootstrap-file', 'bf', InputOption::VALUE_REQUIRED, 'Define the bootstrap file')
                ->addOption('no-code-coverage', 'ncc', InputOption::VALUE_NONE, 'Disable code coverage (big speed increase)')
                ->addOption('max-children-number', 'mcn', InputOption::VALUE_REQUIRED, 'Maximum number of sub-processus which will be run simultaneously')
                ->addOption('loop', 'l', InputOption::VALUE_NONE, 'Enables Atoum loop mode')
                ->addOption('--force-terminal', '', InputOption::VALUE_NONE, '')
                ->addOption('--score-file', '', InputOption::VALUE_REQUIRED, '')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $runner = new Runner('atoum');

        $bundles = $input->getArgument('bundles');
        if (count($bundles) > 0) {
            foreach ($bundles as $k => $bundleName) {
                $bundles[$k] = $this->extractBundleConfigurationFromKernel($bundleName);
            }
        } else {
            $bundles = $this->getContainer()->get('atoum.configuration.bundle.container')->all();
        }

        foreach ($bundles as $bundle) {
            $directories = array_filter($bundle->getDirectories(), function ($dir) {
                return is_dir($dir);
            });

            if (empty($directories)) {
                $output->writeln(sprintf('<error>There is no test found on "%s".</error>', $bundle->getName()));
            }

            foreach ($directories as $directory) {
                $runner->getRunner()->addTestsFromDirectory($directory);
            }
        }

        $defaultBootstrap = sprintf('%s/autoload.php', $this->getApplication()->getKernel()->getRootDir());
        $bootstrap = $input->getOption('bootstrap-file') ? : $defaultBootstrap;

        $this->setAtoumArgument('--bootstrap-file', $bootstrap);

        if ($input->getOption('no-code-coverage')) {
            $this->setAtoumArgument('-ncc');
        }

        if ($input->getOption('max-children-number')) {
            $this->setAtoumArgument('--max-children-number', (int) $input->getOption('max-children-number'));
        }

        if ($input->getOption('loop')) {
            $this->setAtoumArgument('--loop');
        }

        if ($input->getOption('force-terminal')) {
            $this->setAtoumArgument('--force-terminal');
        }

        if ($input->getOption('score-file')) {
            $this->setAtoumArgument('--score-file', $input->getOption('score-file'));
        }

        $runner->run($this->getAtoumArguments());
    }

    /**
     * Set an atoum CLI argument
     *
     * @param string $name
     * @param string $values
     */
    protected function setAtoumArgument($name, $values = null)
    {
        $this->atoumArguments[$name] = $values;
    }

    /**
     * Return inlined atoum cli arguments
     *
     * @return array
     */
    protected function getAtoumArguments()
    {
        $inlinedArguments = array();

        foreach ($this->atoumArguments as $name => $values) {
            $inlinedArguments[] = $name;
            if (null !== $values) {
                $inlinedArguments[] = $values;
            }
        }

        return $inlinedArguments;
    }

    /**
     * @param string $name name
     *
     * @throws \LogicException
     *
     * @return BundleConfiguration
     */
    public function extractBundleConfigurationFromKernel($name)
    {
        $kernelBundles = $this->getContainer()->get('kernel')->getBundles();
        $bundle = null;

        if (preg_match('/Bundle$/', $name)) {
            if (!isset($kernelBundles[$name])) {
                throw new \LogicException(sprintf('Bundle "%s" does not exists or is not activated.', $name));
            }

            $bundle = $kernelBundles[$name];
        } else {
            foreach ($kernelBundles as $kernelBundle) {
                $extension = $kernelBundle->getContainerExtension();

                if ($extension && $extension->getAlias() == $name) {
                    $bundle = $kernelBundle;
                    break;
                }
            }

            if (null === $bundle) {
                throw new \LogicException(sprintf('Bundle with alias "%s" does not exists or is not activated.', $name));
            }
        }

        $bundleContainer = $this->getContainer()->get('atoum.configuration.bundle.container');

        if ($bundleContainer->has($bundle->getName())) {
            return $bundleContainer->get($bundle->getName());
        } else {
            return new BundleConfiguration($bundle->getName(), $this->getDefaultDirectoriesForBundle($bundle));
        }
    }

    /**
     * @param Bundle $bundle bundle
     *
     * @return array
     */
    public function getDefaultDirectoriesForBundle(Bundle $bundle)
    {
        return array(
            sprintf('%s/Tests/Units', $bundle->getPath()),
            sprintf('%s/Tests/Controller', $bundle->getPath()),
        );
    }

}
