<?php

namespace atoum\AtoumBundle\Test\Asserters;

use mageekguy\atoum;
use mageekguy\atoum\asserter;
use mageekguy\atoum\asserters;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Element extends asserters\object
{
    private $parent;
    private $content;
    private $attributes;
    private $exactly;
    private $atLeast;
    private $atMost;
    private $childCount;

    public function __construct(asserter\generator $generator, $parent)
    {
        parent::__construct($generator);

        $this->parent = $parent;
        $this->atLeast = 1;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setWith($value)
    {
        parent::setWith($value, false);

        if (self::isCrawler($this->value) === false) {
            $this->fail(sprintf($this->getLocale()->_('%s is not a crawler'), $this));
        } else {
            $this->pass();
        }

        return $this;
    }

    public function end()
    {
        $nodes = $this->valueIsSet()->value;

        if (isset($this->content)) {
            $nodes = $this->filterContent($nodes);
        }

        if (isset($this->attributes)) {
            $nodes = $this->filterAttributes($nodes);
        }

        if (null !== $this->childCount) {
            $nodes = $this->filterChild($nodes);
        }

        $this->assertCount($nodes);

        return $this->parent;
    }

    public function isEmpty($failMessage = null)
    {
        return $this
            ->hasNoContent()
            ->hasNoChild()
        ;
    }

    public function hasNoContent()
    {
        return $this->withContent('');
    }

    public function withContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    protected function filterContent(DomCrawler $value)
    {
        $content = $this->content;

        return $value->reduce(
            function($node) use ($content) {
                return ($node->nodeValue === $content);
            }
        );
    }

    public function withAttribute($name, $value)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    protected function filterAttributes(DomCrawler $value)
    {
        $attributes = $this->attributes;

        return $value->reduce(
            function(\DOMNode $node) use ($attributes) {
                foreach ($attributes as $name => $value) {
                    if (false === $node->hasAttribute($name) || $value !== $node->getAttribute($name)) {
                        return false;
                    }
                }

                return true;
            }
        );
    }

    public function hasChild($element)
    {
        $this->assertAtLeast($this->valueIsSet()->value);

        $asserter = new Element($this->getGenerator(), $this);
        $asserter->setWith($this->valueIsSet()->value->filter($element));

        return $asserter;
    }

    public function hasChildExactly($count)
    {
        $this->childCount = $count;

        return $this;
    }

    public function hasNoChild()
    {
        return $this->hasChildExactly(0);
    }

    public function getChildCount()
    {
        return $this->childCount;
    }

    protected function filterChild(DomCrawler $value)
    {
        $count = $this->childCount;

        return $value->reduce(
            function(\DOMNode $node) use ($count) {
                $nodes = 0;

                foreach ($node->childNodes as $child) {
                    if (false === $child instanceof \DOMText) {
                        $nodes++;
                    }
                }

                return $nodes === $count;
            }
        );
    }

    protected function assertCount($value)
    {
        if ($this->exactly !== null) {
            $this->assertExactly($value);
        } else {
            if ($this->atLeast !== null) {
                $this->assertAtLeast($value);
            }

            if ($this->atMost !== null) {
                $this->assertAtMost($value);
            }
        }

        return $this;
    }

    public function exactly($count)
    {
        $this->atLeast = null;
        $this->atMost = null;
        $this->exactly = $count;

        return $this;
    }

    public function getExactly()
    {
        return $this->exactly;
    }

    protected function assertExactly($value)
    {
        if (count($value) !== $this->exactly) {
            $this->fail(sprintf($this->getLocale()->_('Found %d element(s) instead of %d'), count($value), $this->exactly));
        } else {
            $this->pass();
        }

        return $this;
    }

    public function atLeast($count)
    {
        $this->exactly = null;
        $this->atLeast = $count;

        return $this;
    }

    public function getAtLeast()
    {
        return $this->atLeast;
    }

    protected function assertAtLeast($value, $failMessage = null)
    {
        if (count($value) >= $this->atLeast) {
            $this->pass();
        } else {
            $this->fail($failMessage !== null ? $failMessage : sprintf($this->getLocale()->_('Expected at least %d element(s), found %d.'), $this->atLeast, count($value)));
        }

        return $this;
    }

    public function atMost($count)
    {
        $this->exactly = null;
        $this->atMost = $count;

        return $this;
    }

    public function getAtMost()
    {
        return $this->atMost;
    }

    protected function assertAtMost($value, $failMessage = null)
    {
        if (count($value) <= $this->atMost) {
            $this->pass();
        } else {
            $this->fail($failMessage !== null ? $failMessage : sprintf($this->getLocale()->_('Expected at most %d element(s), found %d.'), $this->atMost, count($value)));
        }

        return $this;
    }

    protected static function isCrawler($value)
    {
        return ($value instanceof DomCrawler);
    }
}
