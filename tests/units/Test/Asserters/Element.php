<?php
namespace atoum\AtoumBundle\tests\units\Test\Asserters;

require_once __DIR__ . '/../../../bootstrap.php';

use atoum\AtoumBundle\Test\Asserters\Crawler as CrawlerAssert;
use mageekguy\atoum;
use mageekguy\atoum\asserter;
use atoum\AtoumBundle\Test\Asserters\Element as TestedClass;

class Element extends atoum\test
{
    public function testClass()
    {
        $this->testedClass->isSubclassOf('\\mageekguy\\atoum\\asserters\\object');
    }

    public function test__construct()
    {
        $this
            ->if($generator = new asserter\generator())
            ->and($parent = new CrawlerAssert($generator))
            ->and($object = new TestedClass($generator, $parent))
            ->then
                ->object($object->getLocale())->isIdenticalTo($generator->getLocale())
                ->object($object->getGenerator())->isIdenticalTo($generator)
                ->object($object->getParent())->isIdenticalTo($parent)
                ->integer($object->getAtLeast())->isEqualTo(1)
                ->variable($object->getExactly())->isNull()
                ->array($object->getAttributes())->isEmpty()
                ->variable($object->getContent())->isNull()
                ->variable($object->getChildCount())->isNull()
        ;
    }

    public function testSetWith()
    {
        $this
            ->if($generator = new asserter\generator())
            ->and($parent = new CrawlerAssert($generator))
            ->and($object = new TestedClass($generator, $parent))
            ->and($value = uniqid())
            ->then
                ->exception(function() use ($object, $value) {
                    $object->setWith($value);
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('%s is not a crawler'), $object->getTypeOf($value)))
            ->if($crawler = new \Symfony\Component\DomCrawler\Crawler())
            ->then
                ->object($object->setWith($crawler))->isIdenticalTo($object)
        ;
    }

    public function testWithContent()
    {
        $this
            ->if($generator = new asserter\generator())
            ->and($parent = new CrawlerAssert($generator))
            ->and($object = new TestedClass($generator, $parent))
            ->and($content = uniqid())
            ->then
                ->object($object->withContent($content))->isIdenticalTo($object)
                ->string($object->getContent())->isEqualTo($content)
        ;
    }

    public function testHasNoContent()
    {
        $this
            ->if($generator = new asserter\generator())
            ->and($parent = new CrawlerAssert($generator))
            ->and($object = new TestedClass($generator, $parent))
            ->then
                ->object($object->hasNoContent())->isIdenticalTo($object)
                ->string($object->getContent())->isEmpty()
        ;
    }

    public function testHasContent()
    {
        $this
            ->if($generator = new asserter\generator())
            ->and($parent = new CrawlerAssert($generator))
            ->and($object = new TestedClass($generator, $parent))
            ->and($elem = new \DOMElement(uniqid('_'), 'a value'))
            ->and($crawler = new \Symfony\Component\DomCrawler\Crawler(array($elem)))
            ->and($object->setWith($crawler))
            ->then
                ->object($object->hasContent())->isIdenticalTo($object)
            ->if($emptyElem = new \DOMElement(uniqid('_'), ''))
            ->and($crawler = new \Symfony\Component\DomCrawler\Crawler(array($emptyElem)))
            ->and($object->setWith($crawler))
            ->then
                ->exception(function() use($object) {
                    $object->hasContent();
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('Expected any content, found an empty value.')))
            ->if($elem = new \DOMElement(uniqid('_'), 'ab value'))
            ->and($crawler = new \mock\Symfony\Component\DomCrawler\Crawler(array($elem)))
            ->and($this->calling($crawler)->reduce = function(\Closure $closure) use ($elem) {
                $value = array();
                if(false !== $closure($elem)) {
                    $value[] = $elem;
                }

                return new \mock\Symfony\Component\DomCrawler\Crawler($value);
            })
            ->and($object->setWith($crawler))
            ->then
                ->object($object->hasContent())->isIdenticalTo($object)
            ->if($this->calling($crawler)->reduce = function(\Closure $closure) use ($elem, & $tempCrawler) {
                $value = array();
                if(false !== $closure($tempCrawler = new \mock\Symfony\Component\DomCrawler\Crawler($elem))) {
                    $value[] = $elem;
                }

                return new \mock\Symfony\Component\DomCrawler\Crawler($value);
            })
            ->then
                ->object($object->hasContent())->isIdenticalTo($object)
                ->mock($tempCrawler)->call('text')->once()
        ;
    }

    public function testWithAttibute()
    {
        $this
            ->if($generator = new asserter\generator())
            ->and($parent = new CrawlerAssert($generator))
            ->and($object = new TestedClass($generator, $parent))
            ->and($attribute = uniqid())
            ->and($value = uniqid())
            ->then
                ->object($object->withAttribute($attribute, $value))->isIdenticalTo($object)
                ->array($object->getAttributes())->isIdenticalTo(array($attribute => $value))
            ->if($otherAttribute = uniqid())
            ->and($otherValue = uniqid())
            ->then
                ->object($object->withAttribute($otherAttribute, $otherValue))->isIdenticalTo($object)
                ->array($object->getAttributes())->isIdenticalTo(array($attribute => $value, $otherAttribute => $otherValue))
        ;
    }

    public function testExactly()
    {
        $this
            ->if($generator = new asserter\generator())
            ->and($parent = new CrawlerAssert($generator))
            ->and($object = new TestedClass($generator, $parent))
            ->and($count = rand(0, PHP_INT_MAX))
            ->then
                ->object($object->exactly($count))->isIdenticalTo($object)
                ->integer($object->getExactly())->isIdenticalTo($count)
        ;
    }

    public function testAtMost()
    {
        $this
            ->if($generator = new asserter\generator())
            ->and($parent = new CrawlerAssert($generator))
            ->and($object = new TestedClass($generator, $parent))
            ->and($count = rand(0, PHP_INT_MAX))
            ->then
                ->object($object->atMost($count))->isIdenticalTo($object)
                ->integer($object->getAtMost())->isIdenticalTo($count)
        ;
    }

    public function testAtLeast()
    {
        $this
            ->if($generator = new asserter\generator())
            ->and($parent = new CrawlerAssert($generator))
            ->and($object = new TestedClass($generator, $parent))
            ->and($count = rand(0, PHP_INT_MAX))
            ->then
                ->object($object->atLeast($count))->isIdenticalTo($object)
                ->integer($object->getAtLeast())->isIdenticalTo($count)
        ;
    }

    public function testHasNoChild()
    {
        $this
            ->if($generator = new asserter\generator())
            ->and($parent = new CrawlerAssert($generator))
            ->and($object = new TestedClass($generator, $parent))
            ->then
                ->object($object->hasNoChild())->isIdenticalTo($object)
                ->integer($object->getChildCount())->isEqualTo(0)
        ;
    }

    public function testHasChildCount()
    {
        $this
            ->if($generator = new asserter\generator())
            ->and($parent = new CrawlerAssert($generator))
            ->and($object = new TestedClass($generator, $parent))
            ->and($count = rand(0, PHP_INT_MAX))
            ->then
                ->object($object->hasChildExactly($count))->isIdenticalTo($object)
                ->integer($object->getChildCount())->isEqualTo($count)
        ;
    }

    public function testIsEmpty()
    {
        $this
            ->if($generator = new asserter\generator())
            ->and($parent = new CrawlerAssert($generator))
            ->and($object = new TestedClass($generator, $parent))
            ->then
                ->object($object->isEmpty())->isIdenticalTo($object)
                ->integer($object->getChildCount())->isEqualTo(0)
                ->string($object->getContent())->isEmpty()
        ;
    }

    public function testHasChild()
    {
        $this
            ->if($generator = new asserter\generator())
            ->and($parent = new CrawlerAssert($generator))
            ->and($object = new TestedClass($generator, $parent))
            ->and($crawler = new \mock\Symfony\Component\DomCrawler\Crawler())
            ->and($object->setWith($crawler))
            ->then
                ->exception(function() use($object) {
                    $object->hasChild(uniqid());
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('Expected at least %d element(s) matching %s, found %d.'), 1, '*', 0))
            ->if($this->calling($crawler)->count = 1)
            ->then
                ->object($element = $object->hasChild(uniqid()))->isInstanceOf('\\atoum\\AtoumBundle\\Test\\Asserters\\Element')
                ->object($element->getParent())->isIdenticalTo($object)
        ;
    }

    public function testEnd()
    {
        $this
            ->if($generator = new asserter\generator())
            ->and($parent = new CrawlerAssert($generator))
            ->and($object = new TestedClass($generator, $parent))
            ->and($crawler = new \mock\Symfony\Component\DomCrawler\Crawler())
            ->and($this->calling($crawler)->count = 0)
            ->and($object->setWith($crawler))
            ->then
                ->exception(function() use($object) {
                    $object->end();
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('Expected at least %d element(s) matching %s, found %d.'), 1, '*', 0))
            ->if($this->calling($crawler)->count = 1)
            ->then
                ->object($object->end())->isIdenticalTo($parent)
            ->if($object->atLeast(2))
            ->then
                ->exception(function() use($object) {
                    $object->end();
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('Expected at least %d element(s) matching %s, found %d.'), 2, '*', 1))
            ->if($this->calling($crawler)->count = 3)
            ->then
                ->object($object->end())->isIdenticalTo($parent)
            ->if($object->atMost(2))
            ->then
                ->exception(function() use($object) {
                    $object->end();
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('Expected at most %d element(s) matching %s, found %d.'), 2, '*', 3))
            ->if($object->exactly(2))
            ->then
                ->exception(function() use($object) {
                    $object->end();
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('Expected %d element(s) matching %s, found %d.'), 2, '*', 3))
            ->if($this->calling($crawler)->count = 2)
            ->then
                ->object($object->end())->isIdenticalTo($parent)

            ->if($generator = new asserter\generator())
            ->and($parent = new CrawlerAssert($generator))
            ->and($object = new TestedClass($generator, $parent))
            ->and($this->mockGenerator()->shuntParentClassCalls())
            ->and($elem = new \mock\DOMElement(uniqid('_')))
            ->and($otherElem = new \DOMElement(uniqid('_')))
            ->and($crawler = new \Symfony\Component\DomCrawler\Crawler(array($elem, $otherElem)))
            ->and($object->setWith($crawler))
            ->and($object->withAttribute($attr = uniqid(), $value = uniqid()))
            ->then
                ->exception(function() use($object) {
                    $object->end();
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('Expected at least %d element(s) matching %s, found %d.'), 1, '*[' . $attr . '="' . $value . '"]', 0))
            ->if($this->calling($elem)->hasAttribute = true)
            ->and($this->calling($elem)->getAttribute = $value)
            ->then
                ->object($object->end())->isIdenticalTo($parent)
            ->if($object->exactly(2))
            ->then
                ->exception(function() use($object) {
                    $object->end();
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('Expected %d element(s) matching %s, found %d.'), 2, '*[' . $attr . '="' . $value . '"]', 1))
            ->if($object->withContent($content = uniqid()))
            ->then
                ->exception(function() use($object) {
                    $object->end();
                })
                    ->isInstanceOf('mageekguy\atoum\asserter\exception')
                    ->hasMessage(sprintf($generator->getLocale()->_('Expected %d element(s) matching %s, found %d.'), 2, '*[' . $attr . '="' . $value . '"][@content="' . $content . '"]', 0))

        ;
    }
}
