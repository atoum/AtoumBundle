<?php

namespace atoum\AtoumBundle\Test\Units;

use Symfony\Bundle\FrameworkBundle\Client;
use mageekguy\atoum;
use Symfony\Component\CssSelector\CssSelector;

/**
 * WebTestCase
 *
 * @uses Test
 * @author Stephane PY <py.stephane1@gmail.com>
 *
 * @method WebTestCase request(array $options = array(), array $server = array(), array $cookies = array())
 * @method \atoum\AtoumBundle\Test\Asserters\Response get($path, array $parameters = array(), array $files = array(), array $server = array(), $content = null, $changeHistory = true)
 * @method \atoum\AtoumBundle\Test\Asserters\Response head($path, array $parameters = array(), array $files = array(), array $server = array(), $content = null, $changeHistory = true)
 * @method \atoum\AtoumBundle\Test\Asserters\Response post($path, array $parameters = array(), array $files = array(), array $server = array(), $content = null, $changeHistory = true)
 * @method \atoum\AtoumBundle\Test\Asserters\Response put($path, array $parameters = array(), array $files = array(), array $server = array(), $content = null, $changeHistory = true)
 * @method \atoum\AtoumBundle\Test\Asserters\Response patch($path, array $parameters = array(), array $files = array(), array $server = array(), $content = null, $changeHistory = true)
 * @method \atoum\AtoumBundle\Test\Asserters\Response delete($path, array $parameters = array(), array $files = array(), array $server = array(), $content = null, $changeHistory = true)
 * @method \atoum\AtoumBundle\Test\Asserters\Response options($path, array $parameters = array(), array $files = array(), array $server = array(), $content = null, $changeHistory = true)
 * @method \atoum\AtoumBundle\Test\Asserters\Crawler crawler($strict = false)
 */
abstract class WebTestCase extends Test
{
    /**
     * {@inheritdoc}
     */
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
                function(array $options = array(), array $server = array(), array $cookies = array()) use (& $client, $test) {
                    $client = $test->createClient($options, $server, $cookies);

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
                function ($strict = false) use (& $crawler, $generator, $test) {
                    if ($strict) {
                        CssSelector::enableHtmlExtension();
                    } else {
                        CssSelector::disableHtmlExtension();
                    }

                    return $generator->getAsserterInstance('\\atoum\\AtoumBundle\\Test\\Asserters\\Crawler', array($crawler), $test);
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
        $test = $this;

        return function($path, array $parameters = array(), array $files = array(), array $server = array(), $content = null, $changeHistory = true) use (& $client, & $crawler, $method, $generator, $test) {
            /** @var $client \Symfony\Bundle\FrameworkBundle\Client */
            $crawler = $client->request($method, $path, $parameters, $files, $server, $content, $changeHistory);

            return $generator->getAsserterInstance('\\atoum\\AtoumBundle\\Test\\Asserters\\Response', array($client->getResponse()), $test);
        };
    }

    /**
     * Creates a Client.
     *
     * @param array $options An array of options to pass to the createKernel class
     * @param array $server  An array of server parameters
     * @param array $cookies An array of Symfony\Component\BrowserKit\Cookie
     *
     * @return Client A Client instance
     */
    public function createClient(array $options = array(), array $server = array(), array $cookies = array())
    {
        if (null !== $this->kernel && $this->kernelReset) {
            $this->kernel->shutdown();
            $this->kernel->boot();
        }

        if (null === $this->kernel) {
            $this->kernel = $this->createKernel($options);
            $this->kernel->boot();
        }

        $client = $this->kernel->getContainer()->get('test.client');
        $client->setServerParameters($server);

        foreach ($cookies as $cookie) {
            $client->getCookieJar()->set($cookie);
        }

        return $client;
    }
}
