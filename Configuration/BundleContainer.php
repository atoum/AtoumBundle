<?php

namespace atoum\AtoumBundle\Configuration;

/**
 * BundleContainer.
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class BundleContainer
{
    /**
     * @var array<string, Bundle>
     */
    protected array $bundles = [];

    public function add(Bundle $bundle): self
    {
        $this->bundles[$bundle->getName()] = $bundle;

        return $this;
    }

    public function get(string $ident): ?Bundle
    {
        return $this->has($ident) ? $this->bundles[$ident] : null;
    }

    public function has(string $ident): bool
    {
        return isset($this->bundles[$ident]);
    }

    /**
     * @return array<string, Bundle>
     */
    public function all(): array
    {
        return $this->bundles;
    }
}
