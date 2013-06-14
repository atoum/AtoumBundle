<?php

namespace atoum\AtoumBundle\Test\Units;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use atoum\AtoumBundle\Test\Asserters;
use mageekguy\atoum;

/**
 * WebTestCase
 *
 * @uses Test
 * @author Stephane PY <py.stephane1@gmail.com>
 */
abstract class WebTestCase extends Test
{
    /** @var $string */
    protected $class;

    /** @var \Symfony\Component\HttpFoundation\HttpKernelInterface */
    protected $kernel;

    public function __construct(atoum\adapter $adapter = null, atoum\annotations\extractor $annotationExtractor = null, atoum\asserter\generator $asserterGenerator = null, atoum\test\assertion\manager $assertionManager = null, \closure $reflectionClassFactory = null)
    {
        parent::__construct($adapter, $annotationExtractor, $asserterGenerator, $assertionManager, $reflectionClassFactory);

        $generator = $this->getAsserterGenerator();
        $test = $this;
        $crawler = null;
        $client = null;

        $this->getAssertionManager()
            ->setHandler(
                'request',
                function(array $options = array(), array $server = array()) use (& $client, $test, $generator) {
                    $client = $test->createClient($options, $server);

                    return $test;
                }
            )
            ->setHandler('get', $get = $this->getSendRequestHandler($client, $crawler, 'GET'))
            ->setHandler('GET', $get)
            ->setHandler('head', $head = $this->getSendRequestHandler($client, $crawler, 'HEAD'))
            ->setHandler('HEAD', $head)
            ->setHandler('post', $post = $this->getSendRequestHandler($client, $crawler, 'POST'))
            ->setHandler('POST', $post)
            ->setHandler('put', $put = $this->getSendRequestHandler($client, $crawler, 'PUT'))
            ->setHandler('PUT', $put)
            ->setHandler('patch', $patch = $this->getSendRequestHandler($client, $crawler, 'PATCH'))
            ->setHandler('PATCH', $patch)
            ->setHandler('delete', $delete = $this->getSendRequestHandler($client, $crawler, 'DELETE'))
            ->setHandler('DELETE', $delete)
            ->setHandler('options', $options = $this->getSendRequestHandler($client, $crawler, 'OPTIONS'))
            ->setHandler('OPTIONS', $options)
            ->setHandler(
                'crawler',
                function() use (& $crawler, $generator) {
                    $asserter = new Asserters\Crawler($generator);

                    return $asserter->setWith($crawler);
                }
            )
        ;

    }

    /**
     * @param \Symfony\Bundle\FrameworkBundle\Client $client
     * @param \Symfony\Component\DomCrawler\Crawler  $crawler
     * @param string                                 $method
     *
     * @return callable
     */
    protected function getSendRequestHandler(& $client, & $crawler, $method)
    {
        $generator = $this->getAsserterGenerator();

        return function($path, array $parameters = array(), array $files = array(), array $server = array(), $content = null, $changeHistory = true) use (& $client, & $crawler, $method, $generator) {
            /** @var $client \Symfony\Bundle\FrameworkBundle\Client */
            $crawler = $client->request($method, $path, $parameters, $files, $server, $content, $changeHistory);
            $asserter = new Asserters\Response($generator);

            return $asserter->setWith($client->getResponse());
        };
    }

    /**
     * Creates a Client.
     *
     * @param array $options An array of options to pass to the createKernel class
     * @param array $server  An array of server parameters
     *
     * @return Client A Client instance
     */
    public function createClient(array $options = array(), array $server = array())
    {
        if (null !== $this->kernel) {
            $this->kernel->shutdown();
        }

        $this->kernel = $this->createKernel($options);
        $this->kernel->boot();

        $client = $this->kernel->getContainer()->get('test.client');
        $client->setServerParameters($server);

        return $client;
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
     * @return string The Kernel class name
     */
    protected function getKernelClass()
    {
        $dir = $this->getKernelDirectory();

        $finder = new Finder();
        $finder->name('*Kernel.php')->depth(0)->in($dir);
        $results = iterator_to_array($finder);
        if (!count($results)) {
            throw new \RuntimeException('Impossible to find a Kernel file, override the WebTestCase::getKernelDirectory() method or WebTestCase::createKernel() method.');
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
}
