<?php

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use AppBundle\Repository\Traits\DaySortingTrait;
use AppBundle\Repository\Traits\CalendarSortingTrait;

class DayRepository extends BaseRepository
{
    use DaySortingTrait, CalendarSortingTrait {
        DaySortingTrait::setOrder as setDayOrder;
        CalendarSortingTrait::setOrder as setCalendarOrder;
    }

    /**
     * @param array      $criteria
     * @param array|null $orderBy
     * @param null       $limit
     * @param null       $offset
     *
     * @return array
     */
    public function findByWithLike(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $qb = $this
            ->createQueryBuilder('q')
            ->leftJoin('q.calendar', 'c')
        ;

        foreach ($criteria as $key => $value) {
            if (empty($value)) {
                continue;
            }
            $qb->andWhere(
                $qb->expr()->like(
                    'c.'.$key,
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
        $this->setDayOrder($orderBy, $qb);
        $this->setCalendarOrder($orderBy, $qb);
    }
}
