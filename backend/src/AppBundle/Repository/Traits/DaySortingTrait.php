<?php

namespace AppBundle\Repository\Traits;

use Doctrine\ORM\QueryBuilder;

trait DaySortingTrait
{
    /**
     * @param array        $orderBy
     * @param QueryBuilder $qb
     */
    protected function setOrder(array &$orderBy, QueryBuilder $qb)
    {
        foreach ($orderBy as $field => $dir) {
            switch ($field) {
                case 'type':
                    $qb->orderBy('q.type', $dir);
                    unset($orderBy['type']);
                    break;
                case 'working':
                    $qb->orderBy('q.working', $dir);
                    unset($orderBy['working']);
                    break;
                default:
                    continue;
            }
        }

        parent::setOrder($orderBy, $qb);
    }
}
