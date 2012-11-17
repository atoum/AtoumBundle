<?php

namespace atoum\AtoumBundle\Tests\Generator;

class String
{
    const CHARACTERS_ALPHA_LOWER  = 1;
    const CHARACTERS_ALPHA_UPPER  = 2;
    const CHARACTERS_ALPHA        = 3;
    const CHARACTERS_NUMERIC      = 4;
    const CHARACTERS_ALPHANUMERIC = 7;

    /**
     * Return a random string
     *
     * Generator\String::generate()
     *     => random alphanumeric string of random length (between 8 and 16)
     *
     * Generator\String::generate(32)
     *     => random alphanumeric string of length 32
     *
     * Generator\String::generate(10, Generator\String::CHARACTERS_NUMERIC)
     *     => random numeric string of length 10
     *
     * Generator\String::generate(10, Generator\String::CHARACTERS_NUMERIC + Generator\String::CHARACTERS_ALPHA_LOWER)
     *     => random lower case alpha numeric string of length 10
     *
     * Generator\String::generate(32, '0123456789ABCDEF')
     *     => random hexadecimal string of length 32
     *
     * @param  integer          $length
     * @param  integer|string   $characters
     *
     * @return string
     */
    static public function generate($length = null, $characters = self::CHARACTERS_ALPHANUMERIC)
    {
        if($length === null || $length < 0) {
            $length = rand(8, 16);
        }

        if(is_int($characters)) {
            $bits       = $characters;
            $characters = '';

            if($bits & self::CHARACTERS_ALPHA_LOWER) {
                $characters .= 'abcdefghijklmnopqrstuvwxyz';
            }

            if($bits & self::CHARACTERS_ALPHA_UPPER) {
                $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            }

            if($bits & self::CHARACTERS_NUMERIC) {
                $characters .= '0123456789';
            }
        }

        $string           = '';
        $charactersLength = strlen($characters);

        for($i = 0; $i < $length; $i++) {
            $string .= $characters[rand(0, $charactersLength - 1)];
        }

        return $string;
    }
}
