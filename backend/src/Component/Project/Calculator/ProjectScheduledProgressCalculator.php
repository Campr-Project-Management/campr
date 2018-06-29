<?php

namespace Component\Project\Calculator;

use AppBundle\Entity\Project;

class ProjectScheduledProgressCalculator implements ProjectProgressCalculatorInterface
{
    /**
     * @var ProjectScheduledDatesCalculator
     */
    private $scheduledDatesCalculator;

    /**
     * ProjectCostProgressCalculator constructor.
     *
     * @param ProjectScheduledDatesCalculator $scheduledDatesCalculator
     */
    public function __construct(ProjectScheduledDatesCalculator $scheduledDatesCalculator)
    {
        $this->scheduledDatesCalculator = $scheduledDatesCalculator;
    }

    /**
     * @param Project $project
     *
     * @return float
     */
    public function calculate(Project $project): float
    {
        $range = $this->scheduledDatesCalculator->calculate($project);
        if (!$range->getFinish()) {
            return 0;
        }

        $total = $range->getDurationDays();
        $days = $this->getDaysCountBetween($range->getStart(), new \DateTime());
        if ($days <= 0) {
            return 0;
        }

        return round(($days / $total) * 100, 1);
    }

    /**
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return int
     */
    private function getDaysCountBetween(\DateTime $start, \DateTime $end): int
    {
        if ($start >= $end) {
            return 0;
        }

        $start = clone $start;
        $end = clone $end;
        $start->setTime(0, 0, 0);
        $end->setTime(23, 59, 59);

        $interval = $end->diff($start);

        return (int) $interval->format('%a') + 1;
    }
}
