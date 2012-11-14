<?php

namespace Atoum\AtoumBundle\Tests\Units;

use Atoum\AtoumBundle\Tests\Generator;
use mageekguy\atoum;

abstract class Test extends atoum\test
{
    /**
     * @param atoum\factory $factory factory
     */
    public function __construct(atoum\factory $factory = null)
    {
        $this->setTestNamespace('Tests\Units');
        parent::__construct($factory);
    }

    /**
     * Return a random string
     *
     * $this->getRandomString()
     *     => random alphanumeric string of random length (between 8 and 16)
     *
     * $this->getRandomString(32)
     *     => random alphanumeric string of length 32
     *
     * $this->getRandomString(10, Generator\String::CHARACTERS_NUMERIC)
     *     => random numeric string of length 10
     *
     * $this->getRandomString(10, Generator\String::CHARACTERS_NUMERIC + Generator\String::CHARACTERS_ALPHA_LOWER)
     *     => random lower case alpha numeric string of length 10
     *
     * $this->getRandomString(32, '0123456789ABCDEF')
     *     => random hexadecimal string of length 32
     *
     * @param  integer          $length
     * @param  integer|string   $characters
     *
     * @return string
     */
    protected function getRandomString($length = null, $characters = Generator\String::CHARACTERS_ALPHANUMERIC)
    {
        return Generator\String::generate($length, $characters);
    }
}
