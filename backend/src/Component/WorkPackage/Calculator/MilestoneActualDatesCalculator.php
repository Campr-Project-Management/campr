<?php

namespace Component\WorkPackage\Calculator;

use AppBundle\Entity\WorkPackage;
use AppBundle\Repository\WorkPackageRepository;
use Component\Date\DateRange;
use Component\Date\DateRangeInterface;
use Component\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

class MilestoneActualDatesCalculator implements DateRangeCalculatorInterface
{
    /**
     * @var RepositoryInterface
     */
    private $workPackageRepository;

    /**
     * PhaseActualDatesCalculator constructor.
     *
     * @param WorkPackageRepository $workPackageRepository
     */
    public function __construct(WorkPackageRepository $workPackageRepository)
    {
        $this->workPackageRepository = $workPackageRepository;
    }

    /**
     * @param WorkPackage $workPackage
     *
     * @return DateRangeInterface
     */
    public function calculate(WorkPackage $workPackage): DateRangeInterface
    {
        Assert::true($workPackage->isMilestone(), 'WorkPackage is not a milestone');

        $finishAt = $this->workPackageRepository->getMilestoneActualFinishDate($workPackage);

        if ($finishAt) {
            $finishAt = new \DateTime($finishAt);
        }

        if (!$finishAt) {
            $finishAt = $workPackage->getActualFinishAt();
        }

        return new DateRange(null, $finishAt);
    }
}
