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
            ->if($object = new TestedClass($generator = new asserter\generator()))
            ->then
                ->object($object->getLocale())->isIdenticalTo($generator->getLocale())
                ->object($object->getGenerator())->isIdenticalTo($generator)
        ;
    }

    public function testSetWith()
    {
        $this
            ->if($object = new TestedClass($generator = new asserter\generator()))
            ->and($value = uniqid())
            ->then
                ->exception(function() use ($object, $value) {
                    $object->setWith($value);
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('%s is not a response'), $object->getTypeOf($value)))
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
            ->and($this->calling($response)->getStatusCode = function() use(& $status) { return $status; })
            ->and($object->setWith($response))
            ->and($status = rand(400, 500))
            ->then
                ->exception(function() use($object, & $value) {
                    $object->hasStatus($value = rand(200, 300));
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('Status %s is not equal to %s'), $object->getTypeOf($status), $object->getTypeOf($value)))
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
            ->and($this->calling($response)->getAge = function() use(& $age) { return $age; })
            ->and($object->setWith($response))
            ->and($age = rand(0, 100))
            ->then
                ->exception(function() use($object, & $value) {
                    $object->hasAge($value = rand(200, PHP_INT_MAX));
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('Age %s is not equal to %s'), $object->getTypeOf($age), $object->getTypeOf($value)))
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
            ->and($this->calling($response)->getMaxAge = function() use(& $age) { return $age; })
            ->and($object->setWith($response))
            ->and($age = rand(0, 100))
            ->then
                ->exception(function() use($object, & $value) {
                    $object->hasMaxAge($value = rand(200, PHP_INT_MAX));
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('Max age %s is not equal to %s'), $object->getTypeOf($age), $object->getTypeOf($value)))
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
            ->and($this->calling($response)->getCharset = function() use(& $charset) { return $charset; })
            ->and($object->setWith($response))
            ->and($charset = uniqid())
            ->then
                ->exception(function() use($object, & $value) {
                    $object->hasCharset($value = uniqid());
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('Charset %s is not equal to %s'), $object->getTypeOf($charset), $object->getTypeOf($value)))
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
            ->and($this->calling($response)->getContent = function() use(& $content) { return $content; })
            ->and($object->setWith($response))
            ->and($content = uniqid())
            ->then
                ->exception(function() use($object, & $value) {
                    $object->hasContent($value = uniqid());
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('Content %s is not equal to %s'), $object->getTypeOf($content), $object->getTypeOf($value)))
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
            ->and($this->calling($response)->getEtag = function() use(& $etag) { return $etag; })
            ->and($object->setWith($response))
            ->and($etag = uniqid())
            ->then
                ->exception(function() use($object, & $value) {
                    $object->hasEtag($value = uniqid());
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('Etag %s is not equal to %s'), $object->getTypeOf($etag), $object->getTypeOf($value)))
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
            ->and($this->calling($response)->getProtocolVersion = function() use(& $version) { return $version; })
            ->and($object->setWith($response))
            ->and($version = uniqid())
            ->then
                ->exception(function() use($object, & $value) {
                    $object->hasVersion($value = uniqid());
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('Version %s is not equal to %s'), $object->getTypeOf($version), $object->getTypeOf($value)))
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
            ->and($this->calling($response)->getTtl = function() use(& $ttl) { return $ttl; })
            ->and($object->setWith($response))
            ->and($ttl = rand(0, 100))
            ->then
                ->exception(function() use($object, & $value) {
                    $object->hasTtl($value = rand(200, PHP_INT_MAX));
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('TTL %s is not equal to %s'), $object->getTypeOf($ttl), $object->getTypeOf($value)))
            ->if($ttl = uniqid())
            ->then
                ->object($object->hasTtl($ttl))->isIdenticalTo($object)
        ;
    }
}
