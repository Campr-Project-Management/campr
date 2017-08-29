<?php

namespace AppBundle\Repository\Traits;

use Doctrine\ORM\QueryBuilder;

trait MeetingSortingTrait
{
    /**
     * @param array        $orderBy
     * @param QueryBuilder $qb
     */
    protected function setOrder(array &$orderBy, QueryBuilder $qb)
    {
        foreach ($orderBy as $field => $dir) {
            switch ($field) {
                case 'meetingName':
                    if (!in_array('m', $qb->getAllAliases())) {
                        $qb->leftJoin('q.meeting', 'm');
                    }
                    $qb->orderBy('m.name', $dir);
                    unset($orderBy['meetingName']);
                    break;
                default:
                    continue;
            }
        }

        parent::setOrder($orderBy, $qb);
    }
}
