<?php

namespace atoum\AtoumBundle\tests\units\Test\Generator;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use \mageekguy\atoum;
use atoum\AtoumBundle\Test\Generator\String as TestedString;

/**
 * @author Jérémy Romey <jeremy@free-agent.fr>
 */
class String extends atoum\test
{
    public function testGenerate()
    {
        $testedString = new TestedString();

        $length = rand(5, 20);
        $string = $testedString->generate($length, TestedString::CHARACTERS_ALPHANUMERIC);;
        $this
            ->string($string)
            ->match('/^[a-zA-Z0-9]/')
            ->hasLength($length)
        ;

        $length = rand(5, 20);
        $string = $testedString->generate($length, TestedString::CHARACTERS_ALPHA_LOWER);;
        $this
            ->string($string)
            ->match('/^[a-z]/')
            ->hasLength($length)
        ;

        $length = rand(5, 20);
        $string = $testedString->generate($length, TestedString::CHARACTERS_ALPHA_UPPER);;
        $this
            ->string($string)
            ->match('/^[A-Z]/')
            ->hasLength($length)
        ;

        $length = rand(5, 20);
        $string = $testedString->generate($length, TestedString::CHARACTERS_NUMERIC);;
        $this
            ->string($string)
            ->match('/^[0-9]/')
            ->hasLength($length)
        ;
    }
}
