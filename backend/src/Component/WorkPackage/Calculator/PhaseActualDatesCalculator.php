<?php

namespace Component\WorkPackage\Calculator;

use AppBundle\Entity\WorkPackage;
use AppBundle\Repository\WorkPackageRepository;
use Component\Date\DateRange;
use Component\Date\DateRangeInterface;
use Component\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

class PhaseActualDatesCalculator implements DateRangeCalculatorInterface
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
        Assert::true($workPackage->isPhase(), 'WorkPackage is not a phase');

        $startAt = $this->workPackageRepository->getPhaseActualStartDate($workPackage);
        $finishAt = $this->workPackageRepository->getPhaseActualFinishDate($workPackage);

        if ($startAt) {
            $startAt = new \DateTime($startAt);
        }

        if ($finishAt) {
            $finishAt = new \DateTime($finishAt);
        }

        return new DateRange($startAt, $finishAt);
    }
}
