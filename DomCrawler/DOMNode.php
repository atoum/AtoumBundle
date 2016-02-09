<?php
namespace atoum\AtoumBundle\DomCrawler;

use Symfony\Component\DomCrawler;

class DOMNode
{
    /**
     * @var \DOMNode|\Symfony\Component\DomCrawler\Crawler
     */
    protected $node;

    /**
     * @param \DOMNode|\Symfony\Component\DomCrawler\Crawler $node
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($node)
    {
        if (($node instanceof DomCrawler\Crawler || $node instanceof \DOMNode) === false) {
            throw new \InvalidArgumentException('Node should be an instance of either \\DOMNode or \\Symfony\\Component\\DomCrawler\\Crawler, got ' . get_class($node));
        }

        $this->node = $node;
    }

    /**
     * @return \DOMNode|\Symfony\Component\DomCrawler\Crawler
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @return string
     */
    public function text()
    {
        $node = $this->getNode();

        return $node instanceof \DOMNode ? $node->nodeValue : $node->text();
    }

    /**
     * @param string $attribute
     *
     * @return null|string
     */
    public function attr($attribute)
    {
        $node = $this->getNode();
        $value = null;

        if ($node instanceof \DOMNode) {
            $value = $node->getAttribute($attribute);
        } else {
            foreach ($node as $item) {
                if ($item->hasAttribute($attribute)) {
                    $value = $node->attr($attribute);
                }

                break;
            }
        }

        return $value;
    }

    /**
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    public function children()
    {
        $node = $this->getNode();

        return $node instanceof \DOMNode ? new Crawler($node->childNodes) : $node->children();
    }
}
