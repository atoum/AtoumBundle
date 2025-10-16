<?php

namespace atoum\AtoumBundle\Test\Units;

use atoum\atoum;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

/**
 * WebTestCase.
 *
 * @uses \Test
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 *
 * @method WebTestCase                                request(array $options = array(), array $server = array(), array $cookies = array())
 * @method \atoum\AtoumBundle\Test\Asserters\Response get($path, array $parameters = array(), array $files = array(), array $server = array(), $content = null, $changeHistory = true)
 * @method \atoum\AtoumBundle\Test\Asserters\Response head($path, array $parameters = array(), array $files = array(), array $server = array(), $content = null, $changeHistory = true)
 * @method \atoum\AtoumBundle\Test\Asserters\Response post($path, array $parameters = array(), array $files = array(), array $server = array(), $content = null, $changeHistory = true)
 * @method \atoum\AtoumBundle\Test\Asserters\Response put($path, array $parameters = array(), array $files = array(), array $server = array(), $content = null, $changeHistory = true)
 * @method \atoum\AtoumBundle\Test\Asserters\Response patch($path, array $parameters = array(), array $files = array(), array $server = array(), $content = null, $changeHistory = true)
 * @method \atoum\AtoumBundle\Test\Asserters\Response delete($path, array $parameters = array(), array $files = array(), array $server = array(), $content = null, $changeHistory = true)
 * @method \atoum\AtoumBundle\Test\Asserters\Response options($path, array $parameters = array(), array $files = array(), array $server = array(), $content = null, $changeHistory = true)
 * @method \atoum\AtoumBundle\Test\Asserters\Crawler  crawler($strict = false)
 */
abstract class WebTestCase extends Test
{
    public function __construct(?atoum\adapter $adapter = null, ?atoum\annotations\extractor $annotationExtractor = null, ?atoum\asserter\generator $asserterGenerator = null, ?atoum\test\assertion\manager $assertionManager = null, ?\Closure $reflectionClassFactory = null)
    {
        parent::__construct($adapter, $annotationExtractor, $asserterGenerator, $assertionManager, $reflectionClassFactory);

        $generator = $this->getAsserterGenerator();

        $test = $this;
        $crawler = null;
        $client = null;

        $this->getAssertionManager()
            ->setHandler(
                'request',
                function (array $options = [], array $server = [], array $cookies = []) use (&$client, $test) {
                    $client = $test->createClient($options, $server, $cookies);

                    return $test;
                },
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
                function ($strict = false) use (&$crawler, $generator, $test) {
                    if (null === $crawler) {
                        throw new \LogicException(
                            'You must make a request before accessing the crawler. ' .
                            'Use $this->request()->get("/path") or similar methods first.'
                        );
                    }

                    // Note: In Symfony 7+, CSS selector HTML mode is handled automatically
                    // The $strict parameter is kept for backward compatibility but has no effect
                    // as Symfony's DomCrawler now handles HTML parsing optimally by default

                    return $generator->getAsserterInstance('\\atoum\\AtoumBundle\\Test\\Asserters\\Crawler', [$crawler], $test);
                },
            )
        ;
    }

    /**
     * @param KernelBrowser|null                         $client
     * @param \Symfony\Component\DomCrawler\Crawler|null $crawler
     * @param string                                     $method
     *
     * @return callable
     */
    protected function getSendRequestHandler(&$client, &$crawler, $method)
    {
        $generator = $this->getAsserterGenerator();
        $test = $this;

        return function ($path, array $parameters = [], array $files = [], array $server = [], $content = null, $changeHistory = true) use (&$client, &$crawler, $method, $generator, $test) {
            if (null === $client) {
                throw new \LogicException('You must call request() before making HTTP requests.');
            }

            /** 
             * @var KernelBrowser $client
             * Note: request() and getResponse() are available from AbstractBrowser parent class
             */
            // @phpstan-ignore-next-line
            $crawler = $client->request($method, $path, $parameters, $files, $server, $content, $changeHistory);

            // @phpstan-ignore-next-line
            return $generator->getAsserterInstance('\\atoum\\AtoumBundle\\Test\\Asserters\\Response', [$client->getResponse()], $test);
        };
    }

    /**
     * Creates a KernelBrowser.
     *
     * @param array<string, mixed> $options An array of options to pass to the createKernel class
     * @param array<string, mixed> $server  An array of server parameters
     * @param array<mixed> $cookies An array of Symfony\Component\BrowserKit\Cookie
     *
     * @return KernelBrowser A KernelBrowser instance
     */
    public function createClient(array $options = [], array $server = [], array $cookies = []): KernelBrowser
    {
        if (null !== $this->kernel && $this->kernelReset) {
            $this->kernel->shutdown();
            $this->kernel->boot();
        }

        if (null === $this->kernel) {
            /** @var \Symfony\Component\HttpKernel\KernelInterface $kernel */
            $kernel = $this->createKernel($options);
            $kernel->boot();
            $this->kernel = $kernel;
        }

        $client = $this->kernel->getContainer()->get('test.client');
        if (!$client instanceof KernelBrowser) {
            throw new \LogicException('Service "test.client" must return a KernelBrowser instance.');
        }
        
        // setServerParameters and getCookieJar are from AbstractBrowser parent class
        /** @phpstan-ignore-next-line */
        $client->setServerParameters($server);

        foreach ($cookies as $cookie) {
            /** @phpstan-ignore-next-line */
            $client->getCookieJar()->set($cookie);
        }

        return $client;
    }
}
