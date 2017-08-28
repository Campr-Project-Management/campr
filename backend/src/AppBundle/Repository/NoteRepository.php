<?php

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use AppBundle\Repository\Traits\MeetingSortingTrait;
use AppBundle\Repository\Traits\UserSortingTrait;
use AppBundle\Repository\Traits\ProjectSortingTrait;

class NoteRepository extends BaseRepository
{
    use MeetingSortingTrait, UserSortingTrait, ProjectSortingTrait {
        MeetingSortingTrait::setOrder as setMeetingOrder;
        UserSortingTrait::setOrder as setUserOrder;
        ProjectSortingTrait::setOrder as setProjectOrder;
    }

    /**
     * @param array        $orderBy
     * @param QueryBuilder $qb
     */
    public function setOrder(array &$orderBy, QueryBuilder $qb)
    {
        $this->setMeetingOrder($orderBy, $qb);
        $this->setUserOrder($orderBy, $qb);
        $this->setProjectOrder($orderBy, $qb);
    }
}
