<?php

namespace atoum\AtoumBundle\Test\Asserters;

use mageekguy\atoum;
use mageekguy\atoum\asserter;
use mageekguy\atoum\asserters;
use mageekguy\atoum\exceptions;

class Element extends asserters\object
{
    private $parent;
    private $content;
    private $attributes;
    private $count;

    public function __construct(asserter\generator $generator, $parent)
    {
        parent::__construct($generator);

        $this->parent = $parent;
        $this->count = 1;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setWith($value)
    {
        parent::setWith($value, false);

        if (self::isCrawler($this->value) === false)
        {
            $this->fail(sprintf($this->getLocale()->_('%s is not a crawler'), $this));
        }
        else
        {
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

        if (count($nodes) !== $this->count)
        {
            $this->fail(sprintf($this->getLocale()->_('Found %d element(s) instead of %d'), count($nodes), $this->count));
        }
        else
        {
            $this->pass();
        }

        return $this->parent;
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

    public function withAttribute($name, $value)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function exactly($count)
    {
        $this->count = $count;

        return $this;
    }

    public function getCount()
    {
        return $this->count;
    }

    protected function filterContent($value)
    {
        $content = $this->content;

        return $value->reduce(
            function($node) use($content) {
                return ($node->nodeValue === $content);
            }
        );
    }

    protected function filterAttributes($value)
    {
        $attributes = $this->attributes;

        return $value->reduce(
            function($node) use($attributes) {
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
        $asserter = new Element($this->getGenerator(), $this);
        $asserter->setWith($this->valueIsSet()->value->filter($element));

        return $asserter;
    }

    protected static function isCrawler($value)
    {
        return ($value instanceof \Symfony\Component\DomCrawler\Crawler);
    }
}
