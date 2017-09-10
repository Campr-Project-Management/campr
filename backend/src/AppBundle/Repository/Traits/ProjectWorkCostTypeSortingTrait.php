<?php

namespace AppBundle\Repository\Traits;

use Doctrine\ORM\QueryBuilder;

trait ProjectWorkCostTypeSortingTrait
{
    /**
     * @param array        $orderBy
     * @param QueryBuilder $qb
     */
    protected function setOrder(array &$orderBy, QueryBuilder $qb)
    {
        foreach ($orderBy as $field => $dir) {
            switch ($field) {
                case 'projectWorkCostTypeName':
                    $qb->leftJoin('q.projectWorkCostType', 'pwc');
                    $qb->orderBy('pwc.name', $dir);
                    unset($orderBy['projectWorkCostTypeName']);
                    break;
                default:
                    continue;
            }
        }

        parent::setOrder($orderBy, $qb);
    }
}
