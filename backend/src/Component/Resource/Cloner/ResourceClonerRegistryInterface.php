<?php

namespace Component\Resource\Cloner;

use Component\Resource\Model\ResourceInterface;

interface ResourceClonerRegistryInterface
{
    /**
     * @param ResourceClonerInterface $cloner
     */
    public function registerDefault(ResourceClonerInterface $cloner);

    /**
     * @param string                  $class
     * @param ResourceClonerInterface $cloner
     */
    public function register(string $class, ResourceClonerInterface $cloner);

    /**
     * @param string $class
     *
     * @return ResourceClonerInterface|null
     */
    public function get(string $class);

    /**
     * @param ResourceInterface $object
     *
     * @return ResourceClonerInterface|null
     */
    public function getForObject(ResourceInterface $object);
}
