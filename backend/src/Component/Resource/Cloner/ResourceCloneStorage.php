<?php

namespace Component\Resource\Cloner;

use Component\Resource\Model\ResourceInterface;
use Doctrine\Common\Util\ClassUtils;
use Webmozart\Assert\Assert;

class ResourceCloneStorage implements ResourceCloneStorageInterface
{
    /**
     * @var array
     */
    private $storage = [];

    /**
     * @param ResourceInterface $object
     * @param int               $id
     */
    public function set(ResourceInterface $object, int $id)
    {
        Assert::notNull($object);
        Assert::notEmpty($id);

        $class = $this->getClass($object);
        if (!isset($this->storage[$class])) {
            $this->storage[$class] = [];
        }

        if (empty($this->storage[$class][$id])) {
            $this->storage[$class][$id] = $object;
        }
    }

    /**
     * @param ResourceInterface $object
     * @param int               $id
     *
     * @return ResourceInterface
     */
    public function get(ResourceInterface $object, int $id = null): ResourceInterface
    {
        Assert::notNull($object);

        $class = $this->getClass($object);

        if (is_null($id)) {
            $values = $this->storage[$class] ?? [];
            Assert::notEmpty($values, sprintf('Object of class "%s" with ID "%d" not found in storage', $class, $id));

            return array_shift($values);
        }

        Assert::true(
            $this->has($object, $id),
            sprintf('Object of class "%s" with ID "%d" not found in storage', $class, $id)
        );

        return $this->storage[$class][$id];
    }

    /**
     * @param ResourceInterface $object
     *
     * @return int
     */
    public function getId(ResourceInterface $object): int
    {
        Assert::notNull($object);

        $class = $this->getClass($object);

        $values = $this->storage[$class] ?? [];
        Assert::notEmpty($values, sprintf('Object of class "%s" not found in storage', $class));

        return key($values);
    }

    /**
     * @param ResourceInterface $object
     * @param int               $id
     *
     * @return bool
     */
    public function has(ResourceInterface $object, int $id = null): bool
    {
        Assert::notNull($object);

        $class = $this->getClass($object);

        if (is_null($id)) {
            return !empty($this->storage[$class]);
        }

        return !empty($this->storage[$class][$id]);
    }

    /**
     * @return ResourceInterface[]
     */
    public function all(): array
    {
        $data = [];

        foreach ($this->storage as $class => $values) {
            $data = array_merge($data, $values);
        }

        return $data;
    }

    public function clear()
    {
        $this->storage = [];
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
