<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;
use AppBundle\Entity\StatusReport;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Repository\Traits\ProjectSortingTrait;
use AppBundle\Repository\Traits\UserSortingTrait;

class StatusReportRepository extends BaseRepository
{
    use ProjectSortingTrait, UserSortingTrait {
        ProjectSortingTrait::setOrder as setProjectOrder;
        UserSortingTrait::setOrder as setUserOrder;
    }

    /**
     * @param Project $project
     *
     * @return StatusReport[]|null
     */
    public function findLastByProject(Project $project)
    {
        return $this
            ->createQueryBuilder('sr')
            ->where('sr.project = :project')
            ->setParameter('project', $project)
            ->orderBy('sr.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param Project $project
     *
     * @return StatusReport[]
     */
    public function findTrendReports(Project $project)
    {
        $qb = $this->createQueryBuilder('sr');
        $start = new \DateTime();
        $start->modify('-4 week');
        $end = new \DateTime();

        return $qb
            ->andWhere('sr.project = :project')
            ->setParameter('project', $project)
            ->andWhere('sr.createdAt >= :start')
            ->setParameter('start', $start)
            ->andWhere('sr.createdAt <= :end')
            ->setParameter('end', $end)
            ->orderBy('sr.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Project        $project
     * @param \DateTime|null $before
     *
     * @return StatusReport[]
     */
    public function findTrendReportsByProjectBefore(Project $project, \DateTime $before = null)
    {
        if (!$before) {
            $before = new \DateTime();
        }

        return $this
            ->createQueryBuilder('o')
            ->andWhere('o.project = :project and o.createdAt <= :before')
            ->setParameter('project', $project)
            ->setParameter('before', $before)
            ->orderBy('o.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
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
            ->createQueryBuilder('sr')
            ->where('sr.project = :project')
            ->setParameter('project', $project)
            ->orderBy('sr.createdAt', 'DESC');

        if ($select) {
            $qb->select($select);
        }

        if (isset($filters['createdBy'])) {
            $qb->andWhere($qb->expr()->eq('sr.createdBy', $filters['createdBy']));
        }
        if (isset($filters['date'])) {
            $date = new \DateTime($filters['date']);
            $qb
                ->andWhere('sr.createdAt > :date_start')
                ->andWhere('sr.createdAt < :date_end')
                ->setParameter('date_start', $date->format('Y-m-d 00:00:00'))
                ->setParameter('date_end', $date->format('Y-m-d 23:59:59'));
        }

        if (isset($filters['pageSize']) && isset($filters['page'])) {
            $qb
                ->setFirstResult($filters['pageSize'] * ($filters['page'] - 1))
                ->setMaxResults($filters['pageSize']);
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
            ->getQueryBuilderByProjectAndFilters($project, $filters, 'COUNT(DISTINCT sr.id)')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult();
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
