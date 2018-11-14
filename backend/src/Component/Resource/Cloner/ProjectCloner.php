<?php

namespace Component\Resource\Cloner;

use AppBundle\Entity\Project;
use Component\Resource\Model\ResourceInterface;

class ProjectCloner implements ResourceClonerInterface
{
    /**
     * @var ResourceClonerInterface
     */
    private $resourceCloner;

    /**
     * @var ResourceCloneStorageInterface
     */
    private $cloneStorage;

    /**
     * ProjectCloner constructor.
     *
     * @param ResourceClonerInterface       $resourceCloner
     * @param ResourceCloneStorageInterface $cloneStorage
     */
    public function __construct(ResourceClonerInterface $resourceCloner, ResourceCloneStorageInterface $cloneStorage)
    {
        $this->resourceCloner = $resourceCloner;
        $this->cloneStorage = $cloneStorage;
    }

    /**
     * @param ResourceInterface|Project $object
     * @param CloneScopeInterface|null  $scope
     *
     * @return ResourceInterface
     */
    public function clone(ResourceInterface $object, CloneScopeInterface $scope = null): ResourceInterface
    {
        if ($this->cloneStorage->has($object)) {
            $id = $this->cloneStorage->getId($object);
            if ($id !== $object->getId()) {
                return $object;
            }

            return $this->cloneStorage->get($object);
        }

        /** @var Project $clone */
        $clone = $this->resourceCloner->clone($object, $scope);
        if ($clone !== $object) {
            $clone->setNumber(uniqid());
        }

        return $clone;
    }

    /**
     * @param ResourceInterface $object
     *
     * @return bool
     */
    public function supports(ResourceInterface $object): bool
    {
        return $object instanceof Project;
    }
}
