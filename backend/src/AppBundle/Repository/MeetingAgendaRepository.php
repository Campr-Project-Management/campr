<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Meeting;

class MeetingAgendaRepository extends BaseRepository
{
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

        if ($orderBy) {
            foreach ($orderBy as $key => $value) {
                $qb->orderBy('m.'.$key, $value);
            }
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        if ($offset) {
            $qb->setFirstResult($offset);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Return the query builder for all meeting agendas filtered.
     *
     * @param Meeting $meeting
     * @param array   $filters
     * @param string  $select
     *
     * @return QueryBuilder
     */
    public function getQueryBuilderByMeetingAndFilters(Meeting $meeting, $filters = [], $select = null)
    {
        $qb = $this
            ->createQueryBuilder('m')
            ->where('m.meeting = :meeting')
            ->setParameter('meeting', $meeting)
        ;

        if ($select) {
            $qb->select($select);
        }

        if (isset($filters['pageSize']) && isset($filters['page'])) {
            $qb
                ->setFirstResult($filters['pageSize'] * ($filters['page'] - 1))
                ->setMaxResults($filters['pageSize'])
            ;
        }

        return $qb;
    }

    /**
     * Counts the filtered meeting agendas.
     *
     * @param Meeting $meeting
     * @param array   $filters
     *
     * @return int
     */
    public function countTotalByMeetingAndFilters(Meeting $meeting, $filters = [])
    {
        return (int) $this
            ->getQueryBuilderByMeetingAndFilters($meeting, $filters, 'COUNT(DISTINCT m.id)')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
