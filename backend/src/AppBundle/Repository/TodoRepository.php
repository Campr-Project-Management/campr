<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;

class TodoRepository extends BaseRepository
{
    /**
     * Creates the query builder that  returns the project todos.
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
            ->createQueryBuilder('td')
            ->where('td.project = :project')
            ->setParameter('project', $project)
            ->orderBy('td.id', 'ASC')
        ;

        if ($select) {
            $qb->select($select);
        }

        if (isset($filters['status'])) {
            $qb->andWhere('td.status = :status')->setParameter('status', $filters['status']);
        }
        if (isset($filters['dueDate'])) {
            $qb->andWhere('td.dueDate = :dueDate')->setParameter('dueDate', $filters['dueDate']);
        }
        if (isset($filters['responsibility'])) {
            $qb->andWhere('td.responsibility = :responsibility')->setParameter('responsibility', $filters['responsibility']);
        }
        if (isset($filters['todoCategory'])) {
            $qb->andWhere('td.todoCategory = :todoCategory')->setParameter('todoCategory', $filters['todoCategory']);
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
     * Counts the filtered todos.
     *
     * @param Project $project
     * @param array   $filters
     *
     * @return int
     */
    public function countTotalByProjectAndFilters(Project $project, $filters = [])
    {
        return (int) $this
            ->getQueryBuilderByProjectAndFilters($project, $filters, 'COUNT(DISTINCT td.id)')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
