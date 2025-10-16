<?php

namespace atoum\AtoumBundle\Test\Asserters;

use atoum\atoum\asserters;

class Crawler extends asserters\phpObject
{
    /**
     * @param bool $checkType
     *
     * @return $this
     */
    public function setWith(mixed $value, $checkType = true)
    {
        parent::setWith($value, $checkType);

        if (true === $checkType) {
            if (false === self::isCrawler($this->value)) {
                $this->fail(sprintf($this->getLocale()->_('%s is not a crawler'), $this));
            } else {
                $this->pass();
            }
        }

        return $this;
    }

    /**
     * @param string $element
     */
    public function hasElement($element): Element
    {
        $asserter = new Element($this->getGenerator(), $this->getAnalyzer(), $this->getLocale());

        return $asserter
            ->setParent($this)
            ->setWith($this->valueIsSet()->value->filter($element))
        ;
    }

    /**
     * @return bool
     */
    protected static function isCrawler(mixed $value)
    {
        return $value instanceof \Symfony\Component\DomCrawler\Crawler;
    }
}
