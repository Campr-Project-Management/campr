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
        $projectMinStartDate = new \DateTime($_SESSION['projectDates']['minStartDate']);
        $projectNewStartDate = new \DateTime($_SESSION['projectDates']['startDate']);

        $daysDiffProject = $projectNewStartDate->diff($projectMinStartDate)->format('%a');

        /** @var WorkPackage $clone */
        $clone = $this->resourceCloner->clone($object);

        $oldStartAt = $object->getScheduledStartAt();
        $oldFinishAt = $object->getScheduledFinishAt();

        if (!empty($oldStartAt)) {
            // get new startDate
            $newStartAt = new \DateTime($oldStartAt->format("Y-m-d"));
            $newStartAt = $newStartAt->add(new \DateInterval("P{$daysDiffProject}D"));

            // get new finishDate
            $newFinishAt = new \DateTime($newStartAt->format("Y-m-d"));
            $daysPeriod = $oldFinishAt->diff($oldStartAt)->format('%a');
            $newFinishAt = $newFinishAt->add(new \DateInterval("P{$daysPeriod}D"));

            $clone
                ->setScheduledStartAt($newStartAt)
                ->setScheduledFinishAt($newFinishAt)
                ->setForecastStartAt($newStartAt)
                ->setForecastFinishAt($newFinishAt);
        } elseif ($object->getType() == WorkPackage::TYPE_MILESTONE) {
            // get new finishDate for milestone
            $newFinishAt = new \DateTime($oldFinishAt->format("Y-m-d"));
            $newFinishAt = $newFinishAt->add(new \DateInterval("P{$daysDiffProject}D"));

            $clone->setScheduledFinishAt($newFinishAt);
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
