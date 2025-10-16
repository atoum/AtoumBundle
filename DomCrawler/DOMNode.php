<?php

namespace atoum\AtoumBundle\DomCrawler;

use Symfony\Component\DomCrawler;

class DOMNode
{
    protected \DOMNode|DomCrawler\Crawler $node;

    public function __construct(\DOMNode|DomCrawler\Crawler $node)
    {
        $this->node = $node;
    }

    public function getNode(): \DOMNode|DomCrawler\Crawler
    {
        return $this->node;
    }

    public function text(): string
    {
        $node = $this->getNode();

        if ($node instanceof \DOMNode) {
            return $node->nodeValue ?? '';
        }
        
        return $node->text();
    }

    public function attr(string $attribute): ?string
    {
        $node = $this->getNode();
        $value = null;

        if ($node instanceof \DOMElement) {
            $value = $node->getAttribute($attribute);
        } elseif ($node instanceof DomCrawler\Crawler) {
            foreach ($node as $item) {
                if ($item instanceof \DOMElement && $item->hasAttribute($attribute)) {
                    $value = $node->attr($attribute);
                }

                break;
            }
        }

        return $value;
    }

    public function children(): DomCrawler\Crawler
    {
        $node = $this->getNode();

        return $node instanceof \DOMNode ? new Crawler($node->childNodes) : $node->children();
    }
}
