<?php

namespace Component\WorkPackage\Calculator;

use AppBundle\Entity\WorkPackage;
use Component\Repository\RepositoryInterface;

class WorkPackageProgressCalculator implements WorkPackageProgressCalculatorInterface
{
    private $workPackageRepository;

    /**
     * @param RepositoryInterface $workPackageRepository
     */
    public function __construct(RepositoryInterface $workPackageRepository)
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
