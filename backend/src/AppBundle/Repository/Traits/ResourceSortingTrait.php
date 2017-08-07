<?php

namespace AppBundle\Repository\Traits;

use Doctrine\ORM\QueryBuilder;

trait ResourceSortingTrait
{
    /**
     * @param array        $orderBy
     * @param QueryBuilder $qb
     */
    protected function setOrder(array &$orderBy, QueryBuilder $qb)
    {
        foreach ($orderBy as $field => $dir) {
            switch ($field) {
                case 'resourceName':
                    $qb->leftJoin('q.resource', 'r');
                    $qb->orderBy('r.name', $dir);
                    unset($orderBy['resourceName']);
                    break;
                default:
                    continue;
            }
        }

        parent::setOrder($orderBy, $qb);
    }
}
