<?php

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use AppBundle\Repository\Traits\ProjectSortingTrait;

class StatusReportConfigRepository extends BaseRepository
{
    use ProjectSortingTrait {
        ProjectSortingTrait::setOrder as setProjectOrder;
    }

    /**
     * @param array        $orderBy
     * @param QueryBuilder $qb
     */
    public function setOrder(array &$orderBy, QueryBuilder $qb)
    {
        $this->setProjectOrder($orderBy, $qb);
    }
}
