<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Repository\Traits\ProjectSortingTrait;
use AppBundle\Repository\Traits\UserSortingTrait;

class TodoRepository extends BaseRepository
{
    use ProjectSortingTrait, UserSortingTrait {
        ProjectSortingTrait::setOrder as setProjectOrder;
        UserSortingTrait::setOrder as setUserOrder;
    }

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

    /**
     * @param array        $orderBy
     * @param QueryBuilder $qb
     */
    public function setOrder(array &$orderBy, QueryBuilder $qb)
    {
        $this->setProjectOrder($orderBy, $qb);
        $this->setUserOrder($orderBy, $qb);
    }
}
