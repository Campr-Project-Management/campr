<?php

namespace Component\Snapshot\Transformer;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackage;
use AppBundle\Repository\WorkPackageRepository;

class MilestonesTransformer extends AbstractTransformer
{
    /**
     * @var TransformerInterface
     */
    private $milestoneTransformer;

    /**
     * @var WorkPackageRepository
     */
    private $workPackageRepository;

    /**
     * MilestonesTransformer constructor.
     *
     * @param TransformerInterface  $milestoneTransformer
     * @param WorkPackageRepository $workPackageRepository
     */
    public function __construct(
        TransformerInterface $milestoneTransformer,
        WorkPackageRepository $workPackageRepository
    ) {
        $this->milestoneTransformer = $milestoneTransformer;
        $this->workPackageRepository = $workPackageRepository;
    }

    /**
     * @param Project $project
     *
     * @return array
     */
    protected function doTransform($project)
    {
        $milestones = $this->workPackageRepository->getMilestones($project);

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
     * @param WorkPackage[] $milestones
     *
     * @return array
     */
    private function getItems(array $milestones): array
    {
        return array_map(
            function (WorkPackage $milestone) {
                return $this->getItem($milestone);
            },
            $milestones
        );
    }
}
