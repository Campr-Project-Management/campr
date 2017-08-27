<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;
use AppBundle\Repository\Traits\ProjectSortingTrait;

class ProjectDepartmentRepository extends BaseRepository
{
    use ProjectSortingTrait;

    /**
     * Return project departments query.
     *
     * @return Query
     */
    public function getQueryFiltered(Project $project, $filters, $select = null)
    {
        $qb = $this
            ->createQueryBuilder('pd')
            ->where('pd.project = :project')
            ->setParameter('project', $project)
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
     * Counts the filtered departments.
     *
     * @param array $filters
     *
     * @return int
     */
    public function countTotalByFilters(Project $project, $filters = [])
    {
        return (int) $this
            ->getQueryFiltered($project, $filters, 'COUNT(DISTINCT pd.id)')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
