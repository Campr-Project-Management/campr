<?php

namespace Component\Project\Calculator;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackage;
use Component\Calculator\DateRangeCalculatorInterface;

class ProjectPlannedProgressCalculator implements ProjectProgressCalculatorInterface
{
    /**
     * @var DateRangeCalculatorInterface
     */
    private $daysElapsedProgressCalculator;

    /**
     * ProjectPlannedProgressCalculator constructor.
     *
     * @param DateRangeCalculatorInterface $daysElapsedProgressCalculator
     */
    public function __construct(DateRangeCalculatorInterface $daysElapsedProgressCalculator)
    {
        $this->daysElapsedProgressCalculator = $daysElapsedProgressCalculator;
    }

    /**
     * @param Project $project
     *
     * @return float
     */
    public function calculate(Project $project): float
    {
        /** @var WorkPackage[] $workPackages */
        $workPackages = $project->getWorkPackages()->filter(
            function (WorkPackage $wp) {
                return $wp->isTask() && $wp->isRoot();
            }
        );

        $total = 0;
        $count = 0;
        foreach ($workPackages as $workPackage) {
            $start = $workPackage->getScheduledStartAt();
            $end = $workPackage->getScheduledFinishAt();
            if (empty($start) || empty($end)) {
                continue;
            }

            $total += $this->daysElapsedProgressCalculator->calculate($start, $end);
            ++$count;
        }

        if (!$count) {
            return 0;
        }

        return round($total / $count, 4);
    }
}
