<?php

namespace Component\Resource\Cloner;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackage;
use Component\Resource\Model\ResourceInterface;

class WorkPackageCloner implements ResourceClonerInterface
{
    /**
     * @var ResourceClonerInterface
     */
    private $resourceCloner;

    /**
     * WorkPackageCloner constructor.
     *
     * @param ResourceClonerInterface $resourceCloner
     */
    public function __construct(ResourceClonerInterface $resourceCloner)
    {
        $this->resourceCloner = $resourceCloner;
    }

    /**
     * @param ResourceInterface|Project $object
     * @param CloneScopeInterface|null  $scope
     *
     * @return ResourceInterface
     */
    public function clone(ResourceInterface $object, CloneScopeInterface $scope = null): ResourceInterface
    {
        /** @var WorkPackage $clone */
        $clone = $this->resourceCloner->clone($object);

        $oldStartAt = $object->getScheduledStartAt();
        if (!empty($oldStartAt)) {
            // get new startDate
            $newStartAt = new \DateTime();

            // get new finishDate
            $newFinishAt = new \DateTime();
            $oldFinishAt = $object->getScheduledFinishAt();
            $days = $oldFinishAt->diff($oldStartAt)->format('%a');
            $newFinishAt = $newFinishAt->add(new \DateInterval("P{$days}D"));

            $clone
                ->setScheduledStartAt($newStartAt)
                ->setScheduledFinishAt($newFinishAt)
                ->setForecastStartAt($newStartAt)
                ->setForecastFinishAt($newFinishAt);
        }

        if ($clone !== $object) {
            $clone->setExternalId(null);
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
        return $object instanceof WorkPackage;
    }
}
