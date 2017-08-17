<?php

namespace AppBundle\Repository\Traits;

use Doctrine\ORM\QueryBuilder;

trait CalendarSortingTrait
{
    /**
     * @param array        $orderBy
     * @param QueryBuilder $qb
     */
    protected function setOrder(array &$orderBy, QueryBuilder $qb)
    {
        foreach ($orderBy as $field => $dir) {
            switch ($field) {
                case 'parentName':
                    $qb->leftJoin('q.parent', 'p');
                    $qb->orderBy('p.name', $dir);
                    unset($orderBy['parentName']);
                    break;
                case 'calendarName':
                    if (!in_array('c', $qb->getAllAliases())) {
                        $qb->leftJoin('q.calendar', 'c');
                    }
                    $qb->orderBy('c.name', $dir);
                    unset($orderBy['calendarName']);
                    break;
                default:
                    continue;
            }
        }

        parent::setOrder($orderBy, $qb);
    }
}
