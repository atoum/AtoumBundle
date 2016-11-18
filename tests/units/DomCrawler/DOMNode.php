<?php
namespace atoum\AtoumBundle\tests\units\DomCrawler;

use mageekguy\atoum;
use atoum\AtoumBundle\DomCrawler\Crawler;
use atoum\AtoumBundle\DomCrawler\DOMNode as TestedClass;

class DOMNode extends atoum\test
{
    public function test__construct()
    {
        $this
            ->if($node = new \StdClass)
            ->then
                ->exception(function () use ($node) {
                    new TestedClass($node);
                })
                    ->isInstanceOf('\\InvalidArgumentException')
                    ->hasMessage('Node should be an instance of either \\DOMNode or \\Symfony\\Component\\DomCrawler\\Crawler, got ' . get_class($node))
            ->if($node = new \DOMElement(uniqid('_')))
            ->then
                ->object($object = new TestedClass($node))
                ->object($object->getNode())->isIdenticalTo($node)
            ->if($crawler = new \Symfony\Component\DomCrawler\Crawler())
            ->then
                ->object($object = new TestedClass($crawler))
                ->object($object->getNode())->isIdenticalTo($crawler)
        ;
    }

    public function testText()
    {
        $this
            ->if($node = new \DOMElement(uniqid('_'), $value = uniqid()))
            ->and($object = new TestedClass($node))
            ->then
                ->string($object->text())->isEqualTo($value)
            ->if($crawler = new \Symfony\Component\DomCrawler\Crawler(array($node)))
            ->and($object = new TestedClass($crawler))
            ->then
                ->string($object->text())->isEqualTo($value)
        ;
    }

    public function testAttr()
    {
        $this
            ->given($document = new \DOMDocument())
            ->if($node = $document->createElement(uniqid('_')))
            ->and($object = new TestedClass($node))
            ->then
                ->string($object->attr(uniqid()))->isEmpty()
            ->if($crawler = new \Symfony\Component\DomCrawler\Crawler(array($node)))
            ->and($object = new TestedClass($crawler))
            ->then
                ->variable($object->attr(uniqid()))->isNull()
            ->if($a = $node->setAttribute($attr = uniqid('_'), $value = uniqid()))
            ->and($object = new TestedClass($node))
            ->then
                ->string($object->attr($attr))->isEqualTo($value)
            ->if($crawler = new \Symfony\Component\DomCrawler\Crawler(array($node)))
            ->and($object = new TestedClass($crawler))
            ->then
                ->string($object->attr($attr))->isEqualTo($value)
        ;
    }

    public function testChildren()
    {
        $this
            ->given($document = new \DOMDocument())
            ->if($node = $document->createElement(uniqid('_')))
            ->and($child = $document->createElement(uniqid('_')))
            ->and($node->appendChild($child))
            ->and($crawler = new Crawler(array($node)))
            ->and($object = new TestedClass($crawler))
            ->then
                ->object($children = $object->children())->isInstanceOf('\\Symfony\\Component\\DomCrawler\\Crawler')
                ->boolean($children->contains($child))->isTrue()
            ->if($object = new TestedClass($node))
            ->then
                ->object($children = $object->children())->isInstanceOf('\\Symfony\\Component\\DomCrawler\\Crawler')
                ->boolean($children->contains($child))->isTrue()
        ;
    }
}
