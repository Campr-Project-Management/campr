<?php

namespace Component\StatusReport\Aggregator;

use AppBundle\Entity\StatusReport;

interface StatusReportsAggregatorInterface
{
    const TYPE_DAILY = 'daily';

    const TYPE_WEEKLY = 'weekly';

    const TYPE_BIWEEKLY = 'biweekly';

    const TYPE_MONTHLY = 'monthly';

    /**
     * @param StatusReport[] $statusReports
     *
     * @return StatusReport[]
     */
    public function aggregate(array $statusReports): array;

    /**
     * @return string
     */
    public function getType(): string;
}
