<?php

namespace AppBundle\Repository\Traits;

use Doctrine\ORM\QueryBuilder;

trait ProjectTeamSortingTrait
{
    /**
     * @param array        $orderBy
     * @param QueryBuilder $qb
     */
    protected function setOrder(array &$orderBy, QueryBuilder $qb)
    {
        foreach ($orderBy as $field => $dir) {
            switch ($field) {
                case 'projectTeamName':
                    $qb->leftJoin('q.projectTeam', 'pt');
                    $qb->orderBy('pt.name', $dir);
                    unset($orderBy['projectTeamName']);
                    break;
                default:
                    continue;
            }
        }

        parent::setOrder($orderBy, $qb);
    }
}
