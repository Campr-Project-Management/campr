<?php

namespace Component\Snapshot\Transformer;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackage;
use AppBundle\Repository\WorkPackageRepository;

class PhasesTransformer extends AbstractTransformer
{
    /**
     * @var TransformerInterface
     */
    private $phaseTransformer;

    /**
     * @var WorkPackageRepository
     */
    private $workPackageRepository;

    /**
     * PhasesTransformer constructor.
     *
     * @param TransformerInterface  $phaseTransformer
     * @param WorkPackageRepository $workPackageRepository
     */
    public function __construct(
        TransformerInterface $phaseTransformer,
        WorkPackageRepository $workPackageRepository
    ) {
        $this->phaseTransformer = $phaseTransformer;
        $this->workPackageRepository = $workPackageRepository;
    }

    /**
     * @param Project $project
     *
     * @return array
     */
    protected function doTransform($project)
    {
        $milestones = $this->workPackageRepository->getPhases($project);

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
     * @param WorkPackage $phase
     *
     * @return array
     */
    private function getItem(WorkPackage $phase): array
    {
        return $this->phaseTransformer->transform($phase);
    }

    /**
     * @param WorkPackage[] $phases
     *
     * @return array
     */
    private function getItems(array $phases): array
    {
        return array_map(
            function (WorkPackage $phase) {
                return $this->getItem($phase);
            },
            $phases
        );
    }
}
