<?php

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use AppBundle\Repository\Traits\MeetingSortingTrait;
use AppBundle\Repository\Traits\UserSortingTrait;

class MeetingParticipantRepository extends BaseRepository
{
    use MeetingSortingTrait, UserSortingTrait {
        MeetingSortingTrait::setOrder as setMeetingOrder;
        UserSortingTrait::setOrder as setUserOrder;
    }

    public function findByWithLike(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $qb = $this
            ->createQueryBuilder('q')
            ->leftJoin('q.meeting', 'm')
        ;

        foreach ($criteria as $key => $value) {
            if (empty($value)) {
                continue;
            }
            $qb->andWhere(
                $qb->expr()->like(
                    'm.'.$key,
                    $qb->expr()->literal('%'.$value.'%')
                )
            );
        }

        $this->setOrder($orderBy, $qb);

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        if ($offset) {
            $qb->setFirstResult($offset);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param array        $orderBy
     * @param QueryBuilder $qb
     */
    public function setOrder(array &$orderBy, QueryBuilder $qb)
    {
        $this->setMeetingOrder($orderBy, $qb);
        $this->setUserOrder($orderBy, $qb);
    }
}
