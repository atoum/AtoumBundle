<?php

namespace atoum\AtoumBundle\Test\Asserters;

use atoum\AtoumBundle\DomCrawler\DOMNode;
use mageekguy\atoum;
use mageekguy\atoum\tools;
use mageekguy\atoum\asserter;
use mageekguy\atoum\asserters;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Element extends Crawler
{
    /** @var \atoum\AtoumBundle\Test\Asserters\Crawler  */
    private $parent;

    /** @var string */
    private $selector;

    /** @var string */
    private $content;

    /** @var string[] */
    private $attributes = array();

    /** @var int */
    private $exactly;

    /** @var int */
    private $atLeast;

    /** @var int */
    private $atMost;

    /** @var int */
    private $childCount;

    /**
     * @param asserter\generator      $generator
     * @param tools\variable\analyzer $analyzer
     * @param atoum\locale            $locale
     */
    public function __construct(asserter\generator $generator = null, tools\variable\analyzer $analyzer = null, atoum\locale $locale = null)
    {
        parent::__construct($generator, $analyzer, $locale);

        $this->atLeast = 1;
    }

    /**
     * @param Crawler $parent
     *
     * @return $this
     */
    public function setParent(Crawler $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Crawler
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param string $selector
     *
     * @return $this
     */
    public function setSelector($selector)
    {
        $this->selector = $selector;

        return $this;
    }

    /**
     * @param string|null $failMessage
     *
     * @return Crawler
     */
    public function end($failMessage = null)
    {
        $nodes = $this->valueIsSet()->value;

        if (isset($this->content)) {
            $content = $this->getContent();
            $nodes = $this->reduce(
                $nodes,
                function (DOMNode $node) use ($content) {
                    return ($node->text() == $content);
                }
            );
        }

        if (count($this->attributes)) {
            $nodes = $this->filterAttributes($nodes);
        }

        if (null !== $this->childCount) {
            $nodes = $this->filterChild($nodes);
        }

        $this->assertCount($nodes, $failMessage);

        return $this->parent;
    }

    /**
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function isEmpty($failMessage = null)
    {
        return $this
            ->hasNoContent()
            ->hasNoChild()
        ;
    }

    /**
     * @return $this
     */
    public function hasNoContent()
    {
        return $this->withContent('');
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function withContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return $this
     */
    public function hasContent()
    {
        $nodes = $this->reduce(
            $this->valueIsSet()->value,
            function (DOMNode $node) {
                return ($node->text() != '');
            }
        );

        $this->assertCount($nodes, $this->getLocale()->_('Expected any content, found an empty value.'));

        return $this;
    }

    /**
     * @param DomCrawler $nodes
     * @param callable   $closure
     *
     * @return DomCrawler
     */
    protected function reduce(DomCrawler $nodes, \Closure $closure)
    {
        return $nodes->reduce(
            function ($node) use ($closure) {
                return $closure(new DOMNode($node));
            }
        );
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return $this
     */
    public function withAttribute($name, $value)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param DomCrawler $nodes
     *
     * @return DomCrawler
     */
    protected function filterAttributes(DomCrawler $nodes)
    {
        $attributes = $this->attributes;

        return $this->reduce(
            $nodes,
            function (DOMNode $node) use ($attributes) {
                foreach ($attributes as $name => $value) {
                    if ($value !== $node->attr($name)) {
                        return false;
                    }
                }

                return true;
            }
        );
    }

    /**
     * @param string $selector
     *
     * @return Element
     */
    public function hasChild($selector)
    {
        $this->assertAtLeast($this->valueIsSet()->value);

        $asserter = new self($this->getGenerator(), $this->getAnalyzer(), $this->getLocale());
        $asserter
            ->setParent($this)
            ->setWith($this->valueIsSet()->value->filter($selector))
        ;

        return $asserter;
    }

    /**
     * @param int $count
     *
     * @return $this
     */
    public function hasChildExactly($count)
    {
        $this->childCount = $count;

        return $this;
    }

    /**
     * @return $this
     */
    public function hasNoChild()
    {
        return $this->hasChildExactly(0);
    }

    /**
     * @return int|null
     */
    public function getChildCount()
    {
        return $this->childCount;
    }

    /**
     * @param DomCrawler $nodes
     *
     * @return DomCrawler
     */
    protected function filterChild(DomCrawler $nodes)
    {
        $count = $this->childCount;

        return $this->reduce(
            $nodes,
            function (DOMNode $node) use ($count) {
                $nodes = 0;

                foreach ($node->children() as $child) {
                    if (false === $child instanceof \DOMText) {
                        $nodes++;
                    }
                }

                return $nodes === $count;
            }
        );
    }

    /**
     * @param DomCrawler  $value
     * @param string|null $failMessage
     *
     * @return $this
     */
    protected function assertCount(DomCrawler $value, $failMessage = null)
    {
        if ($this->exactly !== null) {
            $this->assertExactly($value, $failMessage);
        } else {
            if ($this->atLeast !== null) {
                $this->assertAtLeast($value, $failMessage);
            }

            if ($this->atMost !== null) {
                $this->assertAtMost($value, $failMessage);
            }
        }

        return $this;
    }

    /**
     * @param int $count
     *
     * @return $this
     */
    public function exactly($count)
    {
        $this->atLeast = null;
        $this->atMost = null;
        $this->exactly = $count;

        return $this;
    }

    /**
     * @return int
     */
    public function getExactly()
    {
        return $this->exactly;
    }

    /**
     * @param DomCrawler  $value
     * @param string|null $failMessage
     *
     * @return $this
     */
    protected function assertExactly(DomCrawler $value, $failMessage = null)
    {
        if (count($value) !== $this->exactly) {
            $this->fail(
                $failMessage !== null ? $failMessage : sprintf(
                    $this->getLocale()->_('Expected %d element(s) matching %s, found %d.'),
                    $this->exactly,
                    $this->getPattern(),
                    count($value)
                )
            );
        } else {
            $this->pass();
        }

        return $this;
    }

    /**
     * @param int $count
     *
     * @return $this
     */
    public function atLeast($count)
    {
        $this->exactly = null;
        $this->atLeast = $count;

        return $this;
    }

    /**
     * @return int
     */
    public function getAtLeast()
    {
        return $this->atLeast;
    }

    /**
     * @param DomCrawler  $value
     * @param string|null $failMessage
     *
     * @return $this
     */
    protected function assertAtLeast(DomCrawler $value, $failMessage = null)
    {
        if (count($value) >= $this->atLeast) {
            $this->pass();
        } else {
            $this->fail(
                $failMessage !== null ? $failMessage : sprintf(
                    $this->getLocale()->_('Expected at least %d element(s) matching %s, found %d.'),
                    $this->atLeast,
                    $this->getPattern(),
                    count($value)
                )
            );
        }

        return $this;
    }

    /**
     * @param int $count
     *
     * @return $this
     */
    public function atMost($count)
    {
        $this->exactly = null;
        $this->atMost = $count;

        return $this;
    }

    /**
     * @return int
     */
    public function getAtMost()
    {
        return $this->atMost;
    }

    /**
     * @param DomCrawler  $value
     * @param string|null $failMessage
     *
     * @return $this
     */
    protected function assertAtMost(DomCrawler $value, $failMessage = null)
    {
        if (count($value) <= $this->atMost) {
            $this->pass();
        } else {
            $this->fail(
                $failMessage !== null ? $failMessage : sprintf(
                    $this->getLocale()->_('Expected at most %d element(s) matching %s, found %d.'),
                    $this->atMost,
                    $this->getPattern(),
                    count($value)
                )
            );
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function getPattern()
    {
        $attributes = '';
        foreach ($this->attributes as $name => $val) {
            $attributes .= '[' . $name . '="' . $val . '"]';
        }

        return sprintf(
            $this->getLocale()->_('%s%s%s'),
            $this->selector ?: '*',
            $attributes,
            $this->getContent() ? '[@content="' . $this->getContent() . '"]' : ''
        );
    }
}
