<?php

namespace Component\Resource\Cloner;

use Component\Resource\Model\ResourceInterface;
use Doctrine\Common\Util\ClassUtils;
use Webmozart\Assert\Assert;

class ResourceClonerRegistry implements ResourceClonerRegistryInterface
{
    /**
     * @var array
     */
    private $registry = [];

    /**
     * @var ResourceClonerInterface
     */
    private $default;

    /**
     * @param string                  $class
     * @param ResourceClonerInterface $cloner
     */
    public function register(string $class, ResourceClonerInterface $cloner)
    {
        Assert::notEmpty($class);
        Assert::classExists($class);

        $this->registry[$class] = $cloner;
    }

    /**
     * @param string $class
     *
     * @return ResourceClonerInterface|null
     */
    public function get(string $class)
    {
        return $this->registry[$class] ?? $this->default;
    }

    /**
     * @param ResourceInterface $object
     *
     * @return ResourceClonerInterface|null
     */
    public function getForObject(ResourceInterface $object)
    {
        return $this->get($this->getClass($object));
    }

    /**
     * @param ResourceClonerInterface $cloner
     */
    public function registerDefault(ResourceClonerInterface $cloner)
    {
        $this->default = $cloner;
    }

    /**
     * @param ResourceInterface $object
     *
     * @return string
     */
    private function getClass(ResourceInterface $object): string
    {
        $class = get_class($object);

        return ClassUtils::getRealClass($class);
    }
}
