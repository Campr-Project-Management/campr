<?php

namespace AppBundle\Repository\Traits;

use Doctrine\ORM\QueryBuilder;

trait WorkPackageCategorySortingTrait
{
    /**
     * @param array        $orderBy
     * @param QueryBuilder $qb
     */
    protected function setOrder(array &$orderBy, QueryBuilder $qb)
    {
        foreach ($orderBy as $field => $dir) {
            switch ($field) {
                case 'workPackageCategoryName':
                    $qb->leftJoin('q.workPackageCategory', 'wpc');
                    $qb->orderBy('wpc.name', $dir);
                    unset($orderBy['workPackageCategoryName']);
                    break;
                default:
                    continue;
            }
        }

        parent::setOrder($orderBy, $qb);
    }
}
