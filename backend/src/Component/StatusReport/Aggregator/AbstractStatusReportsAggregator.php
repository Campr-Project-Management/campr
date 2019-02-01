<?php

namespace Component\StatusReport\Aggregator;

use AppBundle\Entity\StatusReport;

abstract class AbstractStatusReportsAggregator implements StatusReportsAggregatorInterface
{
    /**
     * @param StatusReport[] $reports
     *
     * @return StatusReport[]
     */
    protected function sortByCreatedAt(array $reports): array
    {
        usort(
            $reports,
            function (StatusReport $report1, StatusReport $report2) {
                return $report1->getCreatedAt() <=> $report2->getCreatedAt();
            }
        );

        return $reports;
    }
}
