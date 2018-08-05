<?php

namespace Component\Snapshot\Transformer;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackage;
use Doctrine\Common\Collections\ArrayCollection;

class MilestonesTransformer extends AbstractTransformer
{
    /**
     * @var TransformerInterface
     */
    private $milestoneTransformer;

    /**
     * MilestonesTransformer constructor.
     *
     * @param TransformerInterface $milestoneTransformer
     */
    public function __construct(TransformerInterface $milestoneTransformer)
    {
        $this->milestoneTransformer = $milestoneTransformer;
    }

    /**
     * @param Project $project
     *
     * @return array
     */
    protected function doTransform($project)
    {
        $milestones = $project->getMilestones();

        return [
            'items' => $this->getItems($milestones),
        ];
    }

    /**
     * @param mixed $object
     *
     * @return bool
     */
    public function support($object): bool
    {
        return $object instanceof Project;
    }

    /**
     * @param WorkPackage $milestone
     *
     * @return array
     */
    private function getItem(WorkPackage $milestone): array
    {
        return $this->milestoneTransformer->transform($milestone);
    }

    /**
     * @param ArrayCollection $milestones
     *
     * @return array
     */
    private function getItems(ArrayCollection $milestones): array
    {
        return $milestones
            ->map(
                function (WorkPackage $milestone) {
                    return $this->getItem($milestone);
                }
            )
            ->getValues()
        ;
    }
}
