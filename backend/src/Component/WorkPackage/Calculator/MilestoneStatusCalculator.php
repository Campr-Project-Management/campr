<?php

namespace Component\WorkPackage\Calculator;

use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\WorkPackageStatus;
use AppBundle\Repository\WorkPackageRepository;
use AppBundle\Repository\WorkPackageStatusRepository;
use Webmozart\Assert\Assert;

class MilestoneStatusCalculator implements StatusCalculatorInterface
{
    /**
     * @var WorkPackage
     */
    private $workPackageRepository;

    /**
     * @var WorkPackageStatusRepository
     */
    private $workPackageStatusRepository;

    /**
     * @param WorkPackageRepository       $workPackageRepository
     * @param WorkPackageStatusRepository $workPackageStatusRepository
     */
    public function __construct(
        WorkPackageRepository $workPackageRepository,
        WorkPackageStatusRepository $workPackageStatusRepository
    ) {
        $this->workPackageRepository = $workPackageRepository;
        $this->workPackageStatusRepository = $workPackageStatusRepository;
    }

    /**
     * @param WorkPackage $workPackage
     *
     * @return WorkPackageStatus
     */
    public function calculate(WorkPackage $workPackage): WorkPackageStatus
    {
        Assert::true($workPackage->isMilestone(), 'WorkPackage is not a milestone');

        $status = $workPackage->getWorkPackageStatus();
        if (!$status) {
            $status = $this->workPackageStatusRepository->getDefault();
        }

        return $status;
    }
}
