<?php

namespace Component\Resource\Cloner;

use Component\Resource\Model\ResourceInterface;

interface ResourceCloneStorageInterface
{
    /**
     * @param int               $id
     * @param ResourceInterface $object
     */
    public function set(ResourceInterface $object, int $id);

    /**
     * @param ResourceInterface $object
     * @param int|null          $id
     *
     * @return ResourceInterface
     */
    public function get(ResourceInterface $object, int $id = null): ResourceInterface;

    /**
     * @param ResourceInterface $object
     *
     * @return int
     */
    public function getId(ResourceInterface $object): int;

    /**
     * @param ResourceInterface $object
     * @param int|null          $id
     *
     * @return bool
     */
    public function has(ResourceInterface $object, int $id = null): bool;

    /**
     * @return ResourceInterface[]
     */
    public function all(): array;

    public function clear();
}
