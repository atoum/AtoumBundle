<?php

namespace atoum\AtoumBundle\Configuration;

/**
 * Bundle
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Bundle
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $directories = array();

    /**
     * @param string $name        name
     * @param array  $directories directories
     */
    public function __construct($name, array $directories)
    {
        $this->name        = $name;
        $this->directories = $directories;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getDirectories()
    {
        return $this->directories;
    }
}
