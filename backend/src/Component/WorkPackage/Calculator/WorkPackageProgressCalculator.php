<?php

namespace Component\WorkPackage\Calculator;

use AppBundle\Entity\WorkPackage;
use AppBundle\Repository\WorkPackageRepository;

class WorkPackageProgressCalculator implements WorkPackageProgressCalculatorInterface
{
    /**
     * @var WorkPackage
     */
    private $workPackageRepository;

    /**
     * @param WorkPackageRepository $workPackageRepository
     */
    public function __construct(WorkPackageRepository $workPackageRepository)
    {
        $this->workPackageRepository = $workPackageRepository;
    }

    /**
     * @param WorkPackage $workPackage
     *
     * @return int
     */
    public function calculate(WorkPackage $workPackage): int
    {
        if ($workPackage->isTask() || $workPackage->isTutorial()) {
            return $workPackage->getProgress();
        }

        return $this->workPackageRepository->getWorkPackageProgress($workPackage);
    }
}
