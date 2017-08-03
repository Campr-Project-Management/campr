<?php

namespace AppBundle\Repository\Traits;

use Doctrine\ORM\QueryBuilder;

trait CategorySortingTrait
{
    protected function setOrder(array $orderBy, QueryBuilder $qb)
    {
        foreach ($orderBy as $field => $dir) {
            switch ($field) {
                case 'projectCategoryName':
                    $qb->innerJoin('q.projectCategory', 'c');
                    $qb->orderBy('c.name', $dir);
                    break;
                default:
                    continue;
            }
            unset($orderBy[$field]);
        }

        parent::setOrder($orderBy, $qb);
    }
}
