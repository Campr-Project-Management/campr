<?php

namespace AppBundle\Repository\Traits;

use Doctrine\ORM\QueryBuilder;

trait WorkPackageProjectWorkCostTypeSortingTrait
{
    /**
     * @param array        $orderBy
     * @param QueryBuilder $qb
     */
    protected function setOrder(array &$orderBy, QueryBuilder $qb)
    {
        foreach ($orderBy as $field => $dir) {
            switch ($field) {
                case 'workPackageProjectWorkCostTypeName':
                    $qb->leftJoin('q.workPackageProjectWorkCostType', 'wpp');
                    $qb->orderBy('wpp.name', $dir);
                    unset($orderBy['workPackageProjectWorkCostTypeName']);
                    break;
                default:
                    continue;
            }
        }

        parent::setOrder($orderBy, $qb);
    }
}
