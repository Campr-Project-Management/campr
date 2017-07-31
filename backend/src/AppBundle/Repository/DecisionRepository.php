<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;

class DecisionRepository extends BaseRepository
{
    /**
     * Creates the query builder that  returns the project decisions.
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
            ->createQueryBuilder('d')
            ->where('d.project = :project')
            ->setParameter('project', $project)
            ->orderBy('d.id', 'ASC')
        ;

        if ($select) {
            $qb->select($select);
        }

        if (isset($filters['responsibility'])) {
            $qb->andWhere('d.responsibility = :responsibility')->setParameter('responsibility', $filters['responsibility']);
        }
        if (isset($filters['meeting'])) {
            $qb->andWhere('d.meeting = :meeting')->setParameter('meeting', $filters['meeting']);
        }
        if (isset($filters['decisionCategory'])) {
            $qb->andWhere('d.decisionCategory = :decisionCategory')->setParameter('decisionCategory', $filters['decisionCategory']);
        }
        if (isset($filters['status'])) {
            $qb->andWhere('d.status = :status')->setParameter('status', $filters['status']);
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
     * Counts the filtered decisions.
     *
     * @param Project $project
     * @param array   $filters
     *
     * @return int
     */
    public function countTotalByProjectAndFilters(Project $project, $filters = [])
    {
        return (int) $this
            ->getQueryBuilderByProjectAndFilters($project, $filters, 'COUNT(DISTINCT d.id)')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
