<?php
namespace atoum\AtoumBundle\tests\units\Test\Asserters;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use mageekguy\atoum;
use mageekguy\atoum\asserter;
use atoum\AtoumBundle\Test\Asserters\Crawler as TestedClass;

class Crawler extends atoum\test
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
                    ->hasMessage(sprintf($generator->getLocale()->_('%s is not a crawler'), $object->getTypeOf($value)))
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
