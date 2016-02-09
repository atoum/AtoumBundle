<?php
/**
 * Created by PhpStorm.
 * User: julien.bianchi
 * Date: 09/02/2016
 * Time: 21:29
 */

namespace atoum\AtoumBundle\DomCrawler;

use Symfony\Component\DomCrawler;

class Crawler extends DomCrawler\Crawler
{
    public function contains($object)
    {
        foreach ($this as $node) {
            if ($node === $object) {
                return true;
            }
        }

        return false;
    }
}
