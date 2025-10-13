<?php

namespace atoum\AtoumBundle\Configuration;

/**
 * Bundle.
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Bundle
{
    protected string $name;

    /**
     * @var array<string>
     */
    protected array $directories = [];

    /**
     * @param array<string> $directories
     */
    public function __construct(string $name, array $directories)
    {
        $this->name = $name;
        $this->directories = $directories;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<string>
     */
    public function getDirectories(): array
    {
        return $this->directories;
    }
}
