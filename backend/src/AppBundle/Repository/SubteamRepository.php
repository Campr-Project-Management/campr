<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;

class SubteamRepository extends BaseRepository
{
    /**
     * Return subteam query.
     *
     * @return Query
     */
    public function getQueryFiltered(Project $project, $filters, $select = [])
    {
        $qb = $this
            ->createQueryBuilder('s')
            ->where('s.project = :project')
            ->setParameter('project', $project)
        ;

        if ($select) {
            $qb->select($select);
        }

        if (isset($filters['parent'])) {
            if (!filter_var($filters['parent'], FILTER_VALIDATE_BOOLEAN)) {
                $qb->andWhere($qb->expr()->isNull('s.parent'));
            }
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
     * Counts the filtered subteams.
     *
     * @param array $filters
     *
     * @return int
     */
    public function countTotalByFilters(Project $project, $filters = [])
    {
        return (int) $this
            ->getQueryFiltered($project, $filters, 'COUNT(DISTINCT s.id)')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
