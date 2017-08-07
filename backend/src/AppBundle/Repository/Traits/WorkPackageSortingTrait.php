<?php

namespace AppBundle\Repository\Traits;

use Doctrine\ORM\QueryBuilder;

trait WorkPackageSortingTrait
{
    /**
     * @param array        $orderBy
     * @param QueryBuilder $qb
     */
    protected function setOrder(array &$orderBy, QueryBuilder $qb)
    {
        foreach ($orderBy as $field => $dir) {
            switch ($field) {
                case 'workPackageName':
                    $qb->leftJoin('q.workPackage', 'wp');
                    $qb->orderBy('wp.name', $dir);
                    unset($orderBy['workPackageName']);
                    break;
                default:
                    continue;
            }
        }

        parent::setOrder($orderBy, $qb);
    }
}
