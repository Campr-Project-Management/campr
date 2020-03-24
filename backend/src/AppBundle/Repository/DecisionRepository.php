<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Decision;
use AppBundle\Entity\Meeting;
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

    /**
     * @param Project $project
     *
     * @return array
     */
    public function getAllForStatusReport(Project $project)
    {
        $qb = $this->createQueryBuilder('o');

        $date = new \DateTime('-6 days');
        $date->setTime(0, 0, 0);

        return $qb
            ->andWhere('o.project = :project')
            ->andWhere('(o.done <> 1 or (o.done = 1 and o.doneAt >= :date))')
            ->setParameter('date', $date)
            ->setParameter('project', $project)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Meeting $meeting
     *
     * @return Decision[]
     */
    public function findOpenAndNotExpiredByMeeting(Meeting $meeting)
    {
        $qb = $this->createQueryBuilder('o');

        $createdAtLimit = clone $meeting->getDate();
        $createdAtLimit->add(new \DateInterval('P3D'));
        $createdAtLimit->setTime(23, 59, 59);

        $date = new \DateTime('-6 days');
        $date->setTime(0, 0, 0);

        $qb
            ->andWhere('o.project = :project AND o.createdAt <= :createdAt')
            ->andWhere('(o.done <> 1 or (o.done = 1 and o.doneAt >= :date))')
            ->setParameter('date', $date)
            ->setParameter('createdAt', $createdAtLimit)
            ->setParameter('project', $meeting->getProject())
        ;

        if ($meeting->getDistributionLists()->count()) {
            $qb->andWhere($qb->expr()->eq('o.distributionList', $meeting->getDistributionLists()->first()->getId()));
        }

        return $qb->getQuery()->getResult();
    }
}
