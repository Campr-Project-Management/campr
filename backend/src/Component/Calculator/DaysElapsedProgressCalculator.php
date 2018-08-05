<?php

namespace Component\Calculator;

use Component\Date\DateRange;
use Webmozart\Assert\Assert;

class DaysElapsedProgressCalculator implements DateRangeCalculatorInterface
{
    /**
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return float
     */
    public function calculate(\DateTime $start, \DateTime $end): float
    {
        $start = clone $start;
        $start->setTime(0, 0, 0);

        $end = clone $end;
        $end->setTime(0, 0, 0);

        Assert::lessThanEq($start, $end, sprintf('Start date must be less than or equal to end date'));

        $today = $this->getReferenceDate();
        if ($today >= $end) {
            return 100;
        }

        if ($today <= $start) {
            return 0;
        }

        $dateRange = new DateRange($start, $end);
        $total = $dateRange->getDurationDays();

        $todayRange = new DateRange($start, $today);
        $days = $todayRange->getDurationDays();

        return round(($days / $total) * 100, 4);
    }

    /**
     * @return \DateTime
     */
    protected function getReferenceDate(): \DateTime
    {
        $today = new \DateTime();
        $today->setTime(0, 0, 0);

        return $today;
    }
}
