<?php

namespace atoum\AtoumBundle\Configuration;

/**
 * BundleContainer
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class BundleContainer
{
    /**
     * @var array
     */
    protected $bundles = array();

    /**
     * @param Bundle $bundle bundle
     *
     * @return BundleContainer
     */
    public function add(Bundle $bundle)
    {
        $this->bundles[$bundle->getName()] = $bundle;

        return $this;
    }

    /**
     * @param string $ident ident
     *
     * @return Bundle|null
     */
    public function get($ident)
    {
        return $this->has($ident) ? $this->bundles[$ident] : null;
    }

    /**
     * @param string $ident ident
     *
     * @return boolean
     */
    public function has($ident)
    {
        return isset($this->bundles[$ident]);
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->bundles;
    }
}
