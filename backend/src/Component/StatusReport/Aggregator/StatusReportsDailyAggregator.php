<?php

namespace Component\StatusReport\Aggregator;

use AppBundle\Entity\StatusReport;

class StatusReportsDailyAggregator extends AbstractStatusReportsAggregator
{
    /**
     * @param StatusReport[] $statusReports
     *
     * @return StatusReport[]
     */
    public function aggregate(array $statusReports): array
    {
        $results = [];
        $statusReports = $this->sortByCreatedAt($statusReports);

        foreach ($statusReports as $statusReport) {
            $createdAt = $statusReport->getCreatedAt();
            if (!$createdAt) {
                continue;
            }

            $day = $statusReport->getCreatedAt()->format('Y-m-d');
            $results[$day] = $statusReport;
        }

        return array_values($results);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE_DAILY;
    }
}
