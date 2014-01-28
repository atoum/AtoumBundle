<?php
namespace atoum\AtoumBundle\DomCrawler;

use Symfony\Component\DomCrawler\Crawler;

class DOMNode
{
    protected $node;

    public function __construct($node)
    {
        if (($node instanceof Crawler || $node instanceof \DOMNode) === false) {
            throw new \InvalidArgumentException('Node should be an instance of either \\DOMNode or \\Symfony\\Component\\DomCrawler\\Crawler');
        }

        $this->node = $node;
    }

    public function getNode()
    {
        return $this->node;
    }

    public function text()
    {
        $node = $this->getNode();

        return $node instanceof \DOMNode ? $node->nodeValue : $node->text();
    }

    public function attr($attribute)
    {
        $node = $this->getNode();
        $value = null;

        if ($node instanceof \DOMNode) {
            $value = $node->getAttribute($attribute);
        } else {
            foreach ($node as $item) {
                if ($item->hasAttribute($attribute))
                {
                    $value = $node->attr($attribute);
                }

                break;
            }
        }

        return $value;
    }

    public function children()
    {
        $node = $this->getNode();

        return $node instanceof \DOMNode ? new Crawler($node->childNodes) : $node->children();
    }
}