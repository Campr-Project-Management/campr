<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Repository\Traits\UserSortingTrait;
use AppBundle\Repository\Traits\ProjectSortingTrait;

class DecisionRepository extends BaseRepository
{
    use UserSortingTrait, ProjectSortingTrait {
        UserSortingTrait::setOrder as setUserOrder;
        ProjectSortingTrait::setOrder as setProjectOrder;
    }

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
            ->leftJoin('AppBundle:Meeting', 'meeting', 'WITH', 'meeting.id = d.meeting')
            ->where('d.project = :project')
            ->orWhere('meeting.project = :meetingProject')
            ->setParameter('project', $project)
            ->setParameter('meetingProject', $project)
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
        if (isset($filters['createdAt'])) {
            $qb->andWhere('d.createdAt >= :createdAt')->setParameter('createdAt', $filters['createdAt']);
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

    /**
     * @param array        $orderBy
     * @param QueryBuilder $qb
     */
    public function setOrder(array &$orderBy, QueryBuilder $qb)
    {
        $this->setUserOrder($orderBy, $qb);
        $this->setProjectOrder($orderBy, $qb);
    }
}
