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
                case 'milestoneName':
                    $qb->leftJoin('q.milestone', 'wp');
                    $qb->orderBy('wp.name', $dir);
                    unset($orderBy['milestoneName']);
                    break;
                case 'phaseName':
                    $qb->leftJoin('q.phase', 'wp');
                    $qb->orderBy('wp.name', $dir);
                    unset($orderBy['phaseName']);
                    break;
                case 'workPackageName':
                    if (!in_array('wp', $qb->getAllAliases())) {
                        $qb->leftJoin('q.workPackage', 'wp');
                    }
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
