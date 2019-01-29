<?php

namespace Component\Resource\Factory;

final class Factory implements FactoryInterface
{
    /**
     * @var string
     */
    private $className;

    /**
     * Factory constructor.
     *
     * @param string $className
     */
    public function __construct(string $className)
    {
        $this->className = $className;
    }

    /**
     * @return object
     */
    public function createNew()
    {
        return new $this->className();
    }
}
