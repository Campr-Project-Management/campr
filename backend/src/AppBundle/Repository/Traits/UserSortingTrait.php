<?php

namespace AppBundle\Repository\Traits;

use Doctrine\ORM\QueryBuilder;

trait UserSortingTrait
{
    /**
     * @param array        $orderBy
     * @param QueryBuilder $qb
     */
    protected function setOrder(array &$orderBy, QueryBuilder $qb)
    {
        foreach ($orderBy as $field => $dir) {
            switch ($field) {
                case 'userFullName':
                    $qb->addSelect('CONCAT(u.firstName, \' \', u.lastName) AS HIDDEN full_name');
                    $qb->orderBy('full_name', $dir);
                    unset($orderBy['userFullName']);
                    break;
                case 'userEmail':
                    $qb->orderBy('u.email', $dir);
                    unset($orderBy['userEmail']);
                    break;
                default:
                    continue;
            }
        }

        parent::setOrder($orderBy, $qb);
    }
}
