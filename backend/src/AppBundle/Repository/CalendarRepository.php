<?php

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use AppBundle\Repository\Traits\ProjectSortingTrait;
use AppBundle\Repository\Traits\CalendarSortingTrait;

class CalendarRepository extends BaseRepository
{
    use ProjectSortingTrait, CalendarSortingTrait {
        ProjectSortingTrait::setOrder as setProjectOrder;
        CalendarSortingTrait::setOrder as setCalendarOrder;
    }

    /**
     * @param array        $orderBy
     * @param QueryBuilder $qb
     */
    public function setOrder(array &$orderBy, QueryBuilder $qb)
    {
        $this->setProjectOrder($orderBy, $qb);
        $this->setCalendarOrder($orderBy, $qb);
    }
}
