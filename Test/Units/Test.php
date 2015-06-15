<?php

namespace atoum\AtoumBundle\Test\Units;

use Faker;
use mageekguy\atoum;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\HttpKernelInterface;


abstract class Test extends atoum\test
{
    /** @var $string */
    protected $class;

    /** @var \Symfony\Component\HttpKernel\HttpKernelInterface */
    protected $kernel;

    /** @var boolean */
    protected $kernelReset = true;

    /**
     * {@inheritdoc}
     */
    public function __construct(atoum\adapter $adapter = null, atoum\annotations\extractor $annotationExtractor = null, atoum\asserter\generator $asserterGenerator = null, atoum\test\assertion\manager $assertionManager = null, \closure $reflectionClassFactory = null)
    {
        $this->setTestNamespace('Tests\Units');

        parent::__construct($adapter, $annotationExtractor, $asserterGenerator, $assertionManager, $reflectionClassFactory);
    }

    /**
     * @param atoum\test\assertion\manager $assertionManager
     *
     * @return $this
     */
    public function setAssertionManager(atoum\test\assertion\manager $assertionManager = null)
    {
        $self = $this;

        $returnFaker = function ($locale = 'en_US') use ($self) {
            return $self->getFaker($locale);
        };

        parent::setAssertionManager($assertionManager)
            ->getAssertionManager()
                ->setHandler('faker', $returnFaker)
        ;

        return $this;
    }

    /**
     * @param atoum\annotations\extractor $extractor
     *
     * @return $this|void
     */
    protected function setClassAnnotations(atoum\annotations\extractor $extractor)
    {
        parent::setClassAnnotations($extractor);

        $test = $this;

        $extractor
            ->setHandler('resetKernel', function ($value) use ($test) { $test->enableKernelReset(atoum\annotations\extractor::toBoolean($value)); })
            ->setHandler('noResetKernel', function () use ($test) { $test->enableKernelReset(false); })
        ;
    }

    /**
     * @param string $locale
     *
     * @return Faker\Generator
     */
    public function getFaker($locale = 'en_US')
    {
        return Faker\Factory::create($locale);
    }

    /**
     * Creates a Kernel.
     *
     * Available options:
     *
     *  * environment
     *  * debug
     *
     * @param array $options An array of options
     *
     * @return HttpKernelInterface A HttpKernelInterface instance
     */
    protected function createKernel(array $options = array())
    {
        if (null === $this->class) {
            $this->class = $this->getKernelClass();
        }

        return new $this->class(
            isset($options['environment']) ? $options['environment'] : 'test',
            isset($options['debug']) ? $options['debug'] : true
        );
    }

    /**
     * Attempts to guess the kernel location.
     *
     * When the Kernel is located, the file is required.
     *
     * @throws \RuntimeException
     *
     * @return string The Kernel class name
     */
    protected function getKernelClass()
    {
        $dir = $this->getKernelDirectory();

        $finder = new Finder();
        $finder->name('*Kernel.php')->depth(0)->in($dir);
        $results = iterator_to_array($finder);
        if (!count($results)) {
            throw new \RuntimeException(sprintf('Impossible to find a Kernel file, override the %1$s::getKernelDirectory() method or %1$s::createKernel() method.', get_class($this)));
        }

        $file = current($results);
        $class = $file->getBasename('.php');

        require_once $file;

        return $class;
    }

    /**
     * Override this method if needed
     *
     * @return string
     */
    protected function getKernelDirectory()
    {
        $dir = getcwd().'/app';
        if (!is_dir($dir)) {
            $dir = dirname($dir);
        }

        return $dir;
    }

    /**
     * return Kernel
     *
     * @return Object HttpKernelInterface
     */
    public function getKernel()
    {
        if(null === $this->kernel) {
            $this->kernel = $this->createKernel();
        }

        return $this->kernel;
    }

    /**
     * Enable or disable kernel reseting on client creation.
     *
     * @param boolean $kernelReset
     *
     * @return WebTestCase
     */
    public function enableKernelReset($kernelReset)
    {
        $this->kernelReset = (boolean) $kernelReset;

        return $this;
    }
}
