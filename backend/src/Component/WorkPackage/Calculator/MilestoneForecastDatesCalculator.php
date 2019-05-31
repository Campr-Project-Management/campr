<?php

namespace Component\WorkPackage\Calculator;

use AppBundle\Entity\WorkPackage;
use AppBundle\Repository\WorkPackageRepository;
use Component\Date\DateRange;
use Component\Date\DateRangeInterface;
use Component\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

class MilestoneForecastDatesCalculator implements DateRangeCalculatorInterface
{
    /**
     * @var RepositoryInterface
     */
    private $workPackageRepository;

    /**
     * PhaseForecastDatesCalculator constructor.
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

        $finishAt = $this->workPackageRepository->getMilestoneForecastFinishDate($workPackage);

        if ($finishAt) {
            $finishAt = new \DateTime($finishAt);
        }

        return new DateRange(null, $finishAt);
    }
}
