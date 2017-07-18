<?php

namespace atoum\AtoumBundle\Test\Asserters;

use mageekguy\atoum;
use mageekguy\atoum\asserter;
use mageekguy\atoum\asserters;

class Crawler extends asserters\phpObject
{
    /**
     * @param mixed $value
     * @param bool  $checkType
     *
     * @return $this
     */
    public function setWith($value, $checkType = true)
    {
        parent::setWith($value, $checkType);

        if ($checkType === true) {
            if (self::isCrawler($this->value) === false) {
                $this->fail(sprintf($this->getLocale()->_('%s is not a crawler'), $this));
            } else {
                $this->pass();
            }
        }

        return $this;
    }

    /**
     * @param string $element
     *
     * @return $this
     */
    public function hasElement($element)
    {
        $asserter = new Element($this->getGenerator(), $this->getAnalyzer(), $this->getLocale());

        return $asserter
            ->setParent($this)
            ->setWith($this->valueIsSet()->value->filter($element))
        ;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    protected static function isCrawler($value)
    {
        return ($value instanceof \Symfony\Component\DomCrawler\Crawler);
    }
}
