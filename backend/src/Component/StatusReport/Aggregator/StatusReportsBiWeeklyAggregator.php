<?php

namespace Component\StatusReport\Aggregator;

use AppBundle\Entity\StatusReport;
use Component\Date\DateRange;

class StatusReportsBiWeeklyAggregator extends AbstractStatusReportsAggregator
{
    /**
     * @var StatusReportsWeeklyAggregator
     */
    private $weeklyAggregator;

    /**
     * StatusReportsBiWeeklyAggregator constructor.
     *
     * @param StatusReportsWeeklyAggregator $weeklyAggregator
     */
    public function __construct(StatusReportsWeeklyAggregator $weeklyAggregator)
    {
        $this->weeklyAggregator = $weeklyAggregator;
    }

    /**
     * @param StatusReport[] $statusReports
     *
     * @return StatusReport[]
     */
    public function aggregate(array $statusReports): array
    {
        $results = [];
        if (empty($statusReports)) {
            return [];
        }

        if (1 === count($statusReports)) {
            return $statusReports[0];
        }

        $statusReports = $this->weeklyAggregator->aggregate($statusReports);

        $previousStatusReport = $statusReports[0];
        $results[] = $previousStatusReport;
        foreach (array_slice($statusReports, 1) as $index => $statusReport) {
            $monday1 = $this->getFirstDayOfWeek($previousStatusReport->getCreatedAt());
            $monday2 = $this->getFirstDayOfWeek($statusReport->getCreatedAt());
            $days = $this->getDaysBetween($monday1, $monday2);
            if ($days < 14) {
                continue;
            }

            $results[] = $statusReport;
        }

        return array_values($results);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE_BIWEEKLY;
    }

    /**
     * @param \DateTimeInterface $date
     *
     * @throws \Exception
     *
     * @return \DateTime
     */
    private function getFirstDayOfWeek(\DateTimeInterface $date): \DateTimeInterface
    {
        return new \DateTime(sprintf('Monday this week %s', $date->format('Y-m-d')));
    }

    /**
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $end
     *
     * @return int
     */
    private function getDaysBetween(\DateTimeInterface $start, \DateTimeInterface $end): int
    {
        $range = new DateRange($start, $end);

        return $range->getDurationDays();
    }
}
