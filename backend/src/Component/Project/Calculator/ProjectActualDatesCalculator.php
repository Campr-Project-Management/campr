<?php

namespace Component\Project\Calculator;

use AppBundle\Entity\Project;
use AppBundle\Repository\WorkPackageRepository;
use Component\Date\DateRange;
use Component\Date\DateRangeInterface;

class ProjectActualDatesCalculator implements DateRangeCalculatorInterface
{
    /**
     * @var WorkPackageRepository
     */
    private $workPackageRepository;

    /**
     * ProjectActualDatesCalculator constructor.
     *
     * @param WorkPackageRepository $workPackageRepository
     */
    public function __construct(WorkPackageRepository $workPackageRepository)
    {
        $this->workPackageRepository = $workPackageRepository;
    }

    /**
     * @param Project $project
     *
     * @return DateRangeInterface
     */
    public function calculate(Project $project): DateRangeInterface
    {
        $startAt = $this->workPackageRepository->getProjectActualStartAt($project);
        $finishAt = $this->workPackageRepository->getProjectActualFinishAt($project);

        if ($startAt) {
            $startAt = new \DateTime($startAt);
        }

        if ($finishAt) {
            $finishAt = new \DateTime($finishAt);
        }

        return new DateRange($startAt, $finishAt);
    }
}
