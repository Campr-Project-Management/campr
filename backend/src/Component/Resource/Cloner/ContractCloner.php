<?php

namespace Component\Resource\Cloner;

use AppBundle\Entity\Contract;
use Component\Resource\Model\ResourceInterface;

class ContractCloner implements ResourceClonerInterface
{
    /**
     * @var ResourceClonerInterface
     */
    private $resourceCloner;

    /**
     * ProjectCloner constructor.
     *
     * @param ResourceClonerInterface $resourceCloner
     */
    public function __construct(ResourceClonerInterface $resourceCloner)
    {
        $this->resourceCloner = $resourceCloner;
    }

    /**
     * @param ResourceInterface        $object
     * @param CloneScopeInterface|null $scope
     *
     * @return ResourceInterface
     */
    public function clone(ResourceInterface $object, CloneScopeInterface $scope = null): ResourceInterface
    {
        /** @var Contract $clone */
        $clone = $this->resourceCloner->clone($object);
        if ($clone !== $object) {
            $clone->setName(sprintf('%s %s', $clone->getName(), uniqid()));
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
        return $object instanceof Contract;
    }
}
