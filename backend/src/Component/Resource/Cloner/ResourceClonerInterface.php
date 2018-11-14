<?php

namespace Component\Resource\Cloner;

use Component\Resource\Model\ResourceInterface;

interface ResourceClonerInterface
{
    /**
     * @param ResourceInterface        $object
     * @param CloneScopeInterface|null $scope
     *
     * @return ResourceInterface
     */
    public function clone(ResourceInterface $object, CloneScopeInterface $scope = null): ResourceInterface;

    /**
     * @param ResourceInterface $object
     *
     * @return bool
     */
    public function supports(ResourceInterface $object): bool;
}
