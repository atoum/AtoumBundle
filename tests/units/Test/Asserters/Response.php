<?php
namespace atoum\AtoumBundle\tests\units\Test\Asserters;

require_once __DIR__ . '/../../../bootstrap.php';

use mageekguy\atoum;
use mageekguy\atoum\asserter;
use atoum\AtoumBundle\Test\Asserters\Response as TestedClass;

class Response extends atoum\test
{
    public function testClass()
    {
        $this->testedClass->isSubclassOf('\\mageekguy\\atoum\\asserters\\object');
    }

    public function test__construct()
    {
        $this
            ->if($object = new TestedClass())
            ->then
                ->object($object->getLocale())->isEqualTo(new atoum\locale())
                ->object($object->getGenerator())->isEqualTo(new asserter\generator())
                ->object($object->getAnalyzer())->isEqualTo(new atoum\tools\variable\analyzer())
            ->if($generator = new asserter\generator())
            ->and($locale = new atoum\locale())
            ->and($analyzer = new atoum\tools\variable\analyzer())
            ->and($object = new TestedClass($generator, $analyzer, $locale))
            ->then
                ->object($object->getLocale())->isIdenticalTo($locale)
                ->object($object->getAnalyzer())->isIdenticalTo($analyzer)
                ->object($object->getGenerator())->isIdenticalTo($generator)
        ;
    }

    public function testSetWith()
    {
        $this
            ->if($object = new TestedClass($generator = new asserter\generator()))
            ->and($value = uniqid())
            ->then
                ->exception(function () use ($object, $value) {
                    $object->setWith($value);
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('%s is not a response'), $object->getAnalyzer()->getTypeOf($value)))
            ->if($response = new \mock\Symfony\Component\HttpFoundation\Response())
            ->then
                ->object($object->setWith($response))->isIdenticalTo($object)
        ;
    }

    public function testHasStatus()
    {
        $this
            ->if($object = new TestedClass($generator = new asserter\generator()))
            ->and($response = new \mock\Symfony\Component\HttpFoundation\Response())
            ->and($this->calling($response)->getStatusCode = function () use (& $status) { return $status; })
            ->and($object->setWith($response))
            ->and($status = rand(400, 500))
            ->then
                ->exception(function () use ($object, & $value) {
                    $object->hasStatus($value = rand(200, 300));
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('Status %s is not equal to %s'), $object->getAnalyzer()->getTypeOf($status), $object->getAnalyzer()->getTypeOf($value)))
            ->if($status = rand(200, 300))
            ->then
                ->object($object->hasStatus($status))->isIdenticalTo($object)
        ;
    }

    public function testHasAge()
    {
        $this
            ->if($object = new TestedClass($generator = new asserter\generator()))
            ->and($response = new \mock\Symfony\Component\HttpFoundation\Response())
            ->and($this->calling($response)->getAge = function () use (& $age) { return $age; })
            ->and($object->setWith($response))
            ->and($age = rand(0, 100))
            ->then
                ->exception(function () use ($object, & $value) {
                    $object->hasAge($value = rand(200, PHP_INT_MAX));
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('Age %s is not equal to %s'), $object->getAnalyzer()->getTypeOf($age), $object->getAnalyzer()->getTypeOf($value)))
            ->if($age = rand(200, 300))
            ->then
                ->object($object->hasAge($age))->isIdenticalTo($object)
        ;
    }

    public function testHasMaxAge()
    {
        $this
            ->if($object = new TestedClass($generator = new asserter\generator()))
            ->and($response = new \mock\Symfony\Component\HttpFoundation\Response())
            ->and($this->calling($response)->getMaxAge = function () use (& $age) { return $age; })
            ->and($object->setWith($response))
            ->and($age = rand(0, 100))
            ->then
                ->exception(function () use ($object, & $value) {
                    $object->hasMaxAge($value = rand(200, PHP_INT_MAX));
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('Max age %s is not equal to %s'), $object->getAnalyzer()->getTypeOf($age), $object->getAnalyzer()->getTypeOf($value)))
            ->if($age = rand(200, 300))
            ->then
                ->object($object->hasMaxAge($age))->isIdenticalTo($object)
        ;
    }

    public function testHasCharset()
    {
        $this
            ->if($object = new TestedClass($generator = new asserter\generator()))
            ->and($response = new \mock\Symfony\Component\HttpFoundation\Response())
            ->and($this->calling($response)->getCharset = function () use (& $charset) { return $charset; })
            ->and($object->setWith($response))
            ->and($charset = uniqid())
            ->then
                ->exception(function () use ($object, & $value) {
                    $object->hasCharset($value = uniqid());
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('Charset %s is not equal to %s'), $object->getAnalyzer()->getTypeOf($charset), $object->getAnalyzer()->getTypeOf($value)))
            ->if($charset = uniqid())
            ->then
                ->object($object->hasCharset($charset))->isIdenticalTo($object)
        ;
    }

    public function testHasContent()
    {
        $this
            ->if($object = new TestedClass($generator = new asserter\generator()))
            ->and($response = new \mock\Symfony\Component\HttpFoundation\Response())
            ->and($this->calling($response)->getContent = function () use (& $content) { return $content; })
            ->and($object->setWith($response))
            ->and($content = uniqid())
            ->then
                ->exception(function () use ($object, & $value) {
                    $object->hasContent($value = uniqid());
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('Content %s is not equal to %s'), $object->getAnalyzer()->getTypeOf($content), $object->getAnalyzer()->getTypeOf($value)))
            ->if($content = uniqid())
            ->then
                ->object($object->hasContent($content))->isIdenticalTo($object)
        ;
    }

    public function testHasEtag()
    {
        $this
            ->if($object = new TestedClass($generator = new asserter\generator()))
            ->and($response = new \mock\Symfony\Component\HttpFoundation\Response())
            ->and($this->calling($response)->getEtag = function () use (& $etag) { return $etag; })
            ->and($object->setWith($response))
            ->and($etag = uniqid())
            ->then
                ->exception(function () use ($object, & $value) {
                    $object->hasEtag($value = uniqid());
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('Etag %s is not equal to %s'), $object->getAnalyzer()->getTypeOf($etag), $object->getAnalyzer()->getTypeOf($value)))
            ->if($etag = uniqid())
            ->then
                ->object($object->hasEtag($etag))->isIdenticalTo($object)
        ;
    }

    public function testHasVersion()
    {
        $this
            ->if($object = new TestedClass($generator = new asserter\generator()))
            ->and($response = new \mock\Symfony\Component\HttpFoundation\Response())
            ->and($this->calling($response)->getProtocolVersion = function () use (& $version) { return $version; })
            ->and($object->setWith($response))
            ->and($version = uniqid())
            ->then
                ->exception(function () use ($object, & $value) {
                    $object->hasVersion($value = uniqid());
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('Version %s is not equal to %s'), $object->getAnalyzer()->getTypeOf($version), $object->getAnalyzer()->getTypeOf($value)))
            ->if($version = uniqid())
            ->then
                ->object($object->hasVersion($version))->isIdenticalTo($object)
        ;
    }

    public function testHasTtl()
    {
        $this
            ->if($object = new TestedClass($generator = new asserter\generator()))
            ->and($response = new \mock\Symfony\Component\HttpFoundation\Response())
            ->and($this->calling($response)->getTtl = function () use (& $ttl) { return $ttl; })
            ->and($object->setWith($response))
            ->and($ttl = rand(0, 100))
            ->then
                ->exception(function () use ($object, & $value) {
                    $object->hasTtl($value = rand(200, PHP_INT_MAX));
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('TTL %s is not equal to %s'), $object->getAnalyzer()->getTypeOf($ttl), $object->getAnalyzer()->getTypeOf($value)))
            ->if($ttl = uniqid())
            ->then
                ->object($object->hasTtl($ttl))->isIdenticalTo($object)
        ;
    }

    public function testHasHeader()
    {
        $this
            ->if($object = new \mock\atoum\AtoumBundle\Test\Asserters\Response($generator = new asserter\generator()))
            ->and($response = new \mock\Symfony\Component\HttpFoundation\Response())
            ->and($headers = new \mock\Symfony\Component\HttpFoundation\HeaderBag())
            ->and($this->calling($object)->getHeaders = $headers)
            ->and($object->setWith($response))
            ->and($header = uniqid())
            ->and($value = uniqid())
            ->then
                ->exception(function () use ($object, $header, $value) {
                    $object->hasHeader($header, $value);
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('Value null is not equal to %s for header %s'), $object->getAnalyzer()->getTypeOf($value), $header))
            ->if($this->calling($headers)->get = $actual = uniqid())
            ->then
                ->exception(function () use ($object, $header, $value) {
                    $object->hasHeader($header, $value);
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('Value %s is not equal to %s for header %s'), $object->getAnalyzer()->getTypeOf($actual), $object->getAnalyzer()->getTypeOf($value), $header))
            ->if($this->calling($headers)->get = $value)
            ->then
                ->object($object->hasHeader($header, $value))->isIdenticalTo($object)
        ;
    }
}
