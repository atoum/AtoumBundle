<?php
namespace atoum\AtoumBundle\tests\units\Test\Asserters;

use mageekguy\atoum;
use mageekguy\atoum\asserter;
use atoum\AtoumBundle\Test\Asserters\Crawler as TestedClass;

class Crawler extends atoum\test
{
    public function testClass()
    {
        $this->testedClass->isSubclassOf('\\mageekguy\\atoum\\asserters\\phpObject');
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
                    ->hasMessage(sprintf($generator->getLocale()->_('%s is not an object'), $object->getAnalyzer()->getTypeOf($value)))
            ->if($value = new \StdClass())
            ->then
                ->exception(function () use ($object, $value) {
                    $object->setWith($value);
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('%s is not a crawler'), $object->getAnalyzer()->getTypeOf($value)))
            ->if($crawler = new \mock\Symfony\Component\DomCrawler\Crawler())
            ->then
                ->object($object->setWith($crawler))->isIdenticalTo($object)
        ;
    }

    public function testHasElement()
    {
        $this
            ->if($object = new TestedClass($generator = new asserter\generator()))
            ->and($crawler = new \mock\Symfony\Component\DomCrawler\Crawler())
            ->and($results = new \mock\Symfony\Component\DomCrawler\Crawler())
            ->and($this->calling($crawler)->filter = $results)
            ->and($object->setWith($crawler))
            ->then
                ->object($element = $object->hasElement($selector = uniqid()))->isInstanceOf('\\atoum\\AtoumBundle\\Test\\Asserters\\Element')
                ->object($element->getParent())->isIdenticalTo($object)
                ->object($element->getValue())->isIdenticalTo($results)
        ;
    }
}
