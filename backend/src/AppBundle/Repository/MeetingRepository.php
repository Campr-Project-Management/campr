<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;

class MeetingRepository extends BaseRepository
{
    /**
     * Return the query builder for all project meetings filtered.
     *
     * @param Project $project
     * @param array   $filters
     * @param string  $select
     *
     * @return QueryBuilder
     */
    public function getQueryBuilderByProjectAndFilters(Project $project, $filters = [], $select = null)
    {
        $qb = $this
            ->createQueryBuilder('m')
            ->innerJoin('m.project', 'p')
            ->where('p.id = :project')
            ->setParameter('project', $project)
            ->orderBy('m.date', 'DESC')
        ;

        if ($select) {
            $qb->select($select);
        }

        if (isset($filters['event'])) {
            $qb
                ->andWhere(
                    $qb->expr()->like(
                        'm.name',
                        $qb->expr()->literal('%'.$filters['event'].'%')
                    )
                )
            ;
        }

        if (isset($filters['category'])) {
            $qb->andWhere('m.meetingCategory = :category')->setParameter('category', $filters['category']);
        }

        if (isset($filters['date'])) {
            $qb->andWhere('m.date = :date')->setParameter('date', $filters['date']);
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
     * Counts the filtered workpackages.
     *
     * @param Project $project
     * @param array   $filters
     *
     * @return int
     */
    public function countTotalByProjectAndFilters(Project $project, $filters = [])
    {
        return (int) $this
            ->getQueryBuilderByProjectAndFilters($project, $filters, 'COUNT(DISTINCT m.id)')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
