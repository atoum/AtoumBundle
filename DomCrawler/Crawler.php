<?php

namespace atoum\AtoumBundle\DomCrawler;

use Symfony\Component\DomCrawler;

class Crawler extends DomCrawler\Crawler
{
    public function contains(mixed $object): bool
    {
        foreach ($this as $node) {
            if ($node === $object) {
                return true;
            }
        }

        return false;
    }
}
