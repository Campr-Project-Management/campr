<?php

namespace AppBundle\Repository;

use AppBundle\Entity\ProjectCloseDown;

class CloseDownActionRepository extends BaseRepository
{
    /**
     * Creates the query builder that  returns the project close down actions.
     *
     * @param ProjectCloseDown $project
     * @param array            $filters
     * @param string           $select
     *
     * @return QueryBuilder
     */
    public function getQueryBuilderByProjectCloseDownAndFilters(ProjectCloseDown $projectCloseDown, $filters = [], $select = null)
    {
        $qb = $this
            ->createQueryBuilder('cda')
            ->where('cda.projectCloseDown = :projectCloseDown')
            ->setParameter('projectCloseDown', $projectCloseDown)
            ->orderBy('cda.id', 'ASC')
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
     * Counts the filtered decisions.
     *
     * @param Project $project
     * @param array   $filters
     *
     * @return int
     */
    public function countTotalByProjectCloseDownAndFilters(ProjectCloseDown $projectCloseDown, $filters = [])
    {
        return (int) $this
            ->getQueryBuilderByProjectCloseDownAndFilters($projectCloseDown, $filters, 'COUNT(DISTINCT cda.id)')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
