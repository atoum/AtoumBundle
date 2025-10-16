<?php

namespace atoum\AtoumBundle\Command;

use atoum\AtoumBundle\Configuration\Bundle as BundleConfiguration;
use atoum\AtoumBundle\Configuration\BundleContainer;
use atoum\AtoumBundle\Scripts\Runner;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * AtoumCommand.
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
#[AsCommand(name: 'atoum', description: 'Launch atoum tests.')]
class AtoumCommand extends Command
{
    /**
     * @var array<string, string|int|null> List of atoum CLI runner arguments
     */
    private array $atoumArguments = [];

    public function __construct(
        private readonly BundleContainer $bundleContainer,
        private readonly KernelInterface $kernel,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
                ->setHelp(
                    <<<EOF
                        <info>Symfony 7+ Modern Usage (recommended):</info>

                        Test specific directories:
                        <comment>bin/console atoum --directory=src/Tests</comment>
                        <comment>bin/console atoum --directory=tests</comment>
                        <comment>bin/console atoum --directory=src/Tests --directory=tests/Integration</comment>

                        <info>Legacy Bundle Usage:</info>

                        Launch tests of AcmeFooBundle:
                        <comment>bin/console atoum AcmeFooBundle</comment>

                        Launch tests of many bundles:
                        <comment>bin/console atoum AcmeFooBundle bundle_alias_extension ...</comment>

                        Launch tests of all bundles defined in configuration:
                        <comment>bin/console atoum</comment>

                        <info>Note:</info> If no bundles or directories are specified, tests from configured bundles will be executed.

                        EOF
                )
                ->addArgument('bundles', InputArgument::IS_ARRAY, 'Launch tests of these bundles (legacy).')
                ->addOption('directory', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Test directory path(s) - Symfony 7+ modern approach')
                ->addOption('bootstrap-file', 'bf', InputOption::VALUE_REQUIRED, 'Define the bootstrap file')
                ->addOption('no-code-coverage', 'ncc', InputOption::VALUE_NONE, 'Disable code coverage (big speed increase)')
                ->addOption('use-light-report', null, InputOption::VALUE_NONE, 'Reduce the output generated')
                ->addOption('max-children-number', 'mcn', InputOption::VALUE_REQUIRED, 'Maximum number of sub-processus which will be run simultaneously')
                ->addOption('xunit-report-file', 'xrf', InputOption::VALUE_REQUIRED, 'Define the xunit report file')
                ->addOption('clover-report-file', 'crf', InputOption::VALUE_REQUIRED, 'Define the clover report file')
                ->addOption('loop', 'l', InputOption::VALUE_NONE, 'Enables Atoum loop mode')
                ->addOption('force-terminal', '', InputOption::VALUE_NONE, '')
                ->addOption('score-file', '', InputOption::VALUE_REQUIRED, '')
                ->addOption('debug', 'd', InputOption::VALUE_NONE, 'Enables Atoum debug mode')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $runner = new Runner('atoum');

        // Modern approach: test directories directly
        $directories = $input->getOption('directory');
        if (!empty($directories)) {
            foreach ($directories as $directory) {
                $fullPath = $this->resolveDirectoryPath($directory);

                if (!is_dir($fullPath)) {
                    $output->writeln(sprintf('<error>Directory "%s" does not exist.</error>', $directory));

                    continue;
                }

                $output->writeln(sprintf('<info>Adding tests from directory: %s</info>', $fullPath));
                $runner->getRunner()->addTestsFromDirectory($fullPath);
            }
        } else {
            // Legacy approach: test bundles
            $bundles = $input->getArgument('bundles');
            if (count($bundles) > 0) {
                foreach ($bundles as $k => $bundleName) {
                    $bundles[$k] = $this->extractBundleConfigurationFromKernel($bundleName);
                }
            } else {
                $bundles = $this->bundleContainer->all();
            }

            foreach ($bundles as $bundle) {
                /** @var array<string> $bundleDirectories */
                $bundleDirectories = array_filter($bundle->getDirectories(), function (string $dir): bool {
                    return is_dir($dir);
                });

                if (empty($bundleDirectories)) {
                    $output->writeln(sprintf('<error>There is no test found on "%s".</error>', $bundle->getName()));
                    continue;
                }

                foreach ($bundleDirectories as $directory) {
                    $runner->getRunner()->addTestsFromDirectory($directory);
                }
            }
        }

        $defaultBootstrap = sprintf('%s/vendor/autoload.php', $this->kernel->getProjectDir());
        $bootstrap = $input->getOption('bootstrap-file') ?: $defaultBootstrap;

        $this->setAtoumArgument('--bootstrap-file', $bootstrap);

        if ($input->getOption('no-code-coverage')) {
            $this->setAtoumArgument('-ncc');
        }

        if ($input->getOption('use-light-report')) {
            $this->setAtoumArgument('-ulr');
        }

        if ($input->getOption('max-children-number')) {
            $this->setAtoumArgument('--max-children-number', (int) $input->getOption('max-children-number'));
        }

        if ($input->getOption('xunit-report-file')) {
            $xunit = new \atoum\atoum\reports\asynchronous\xunit();
            $runner->addReport($xunit);
            $writerXunit = new \atoum\atoum\writers\file($input->getOption('xunit-report-file'));
            $xunit->addWriter($writerXunit);
        }

        if ($input->getOption('clover-report-file')) {
            $clover = new \atoum\atoum\reports\asynchronous\clover();
            $runner->addReport($clover);
            $writerClover = new \atoum\atoum\writers\file($input->getOption('clover-report-file'));
            $clover->addWriter($writerClover);
        }

        if ($input->getOption('xunit-report-file') || $input->getOption('clover-report-file')) {
            $reportCli = new \atoum\atoum\reports\realtime\cli();
            $runner->addReport($reportCli);
            $writerCli = new \atoum\atoum\writers\std\out();
            $reportCli->addWriter($writerCli);
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

        if ($input->getOption('debug')) {
            $this->setAtoumArgument('--debug');
        }

        try {
            $score = $runner->run($this->getAtoumArguments())->getRunner()->getScore();

            $isSuccess = $score->getFailNumber() <= 0 && $score->getErrorNumber() <= 0 && $score->getExceptionNumber() <= 0;

            if ($runner->shouldFailIfVoidMethods() && $score->getVoidMethodNumber() > 0) {
                $isSuccess = false;
            }

            if ($runner->shouldFailIfSkippedMethods() && $score->getSkippedMethodNumber() > 0) {
                $isSuccess = false;
            }

            return $isSuccess ? 0 : 1;
        } catch (\Exception $exception) {
            $output->writeln(sprintf('<error>%s</error>', $exception->getMessage()));
            if ($output->isVerbose()) {
                $output->writeln($exception->getTraceAsString());
            }

            return 2;
        }
    }

    /**
     * Set an atoum CLI argument.
     */
    protected function setAtoumArgument(string $name, string|int|null $values = null): void
    {
        $this->atoumArguments[$name] = $values;
    }

    /**
     * Return inlined atoum cli arguments.
     * 
     * @return array<int, string|int>
     */
    protected function getAtoumArguments(): array
    {
        $inlinedArguments = [];

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
     */
    public function extractBundleConfigurationFromKernel(string $name): BundleConfiguration
    {
        $kernelBundles = $this->kernel->getBundles();
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

        if ($this->bundleContainer->has($bundle->getName())) {
            $bundleConfig = $this->bundleContainer->get($bundle->getName());
            if (null === $bundleConfig) {
                throw new \LogicException(sprintf('Bundle configuration for "%s" should not be null.', $bundle->getName()));
            }
            return $bundleConfig;
        }
        
        return new BundleConfiguration($bundle->getName(), $this->getDefaultDirectoriesForBundle($bundle));
    }

    /**
     * @param BundleInterface $bundle bundle
     * @return array<string>
     */
    public function getDefaultDirectoriesForBundle(BundleInterface $bundle): array
    {
        return [
            sprintf('%s/Tests/Units', $bundle->getPath()),
            sprintf('%s/Tests/Controller', $bundle->getPath()),
        ];
    }

    /**
     * Resolve directory path (supports both absolute and relative paths).
     *
     * @param string $directory The directory path to resolve
     *
     * @return string The resolved absolute path
     */
    private function resolveDirectoryPath(string $directory): string
    {
        // If absolute path, return as-is
        if ('/' === $directory[0]) {
            return $directory;
        }

        // Relative path: resolve from project root
        return $this->kernel->getProjectDir().'/'.ltrim($directory, '/');
    }
}
