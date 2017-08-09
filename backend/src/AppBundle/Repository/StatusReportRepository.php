<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;

class StatusReportRepository extends BaseRepository
{
    public function findLastByProject(Project $project)
    {
        return $this
            ->createQueryBuilder('sr')
            ->where('sr.project = :project')
            ->setParameter('project', $project)
            ->orderBy('sr.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

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
            ->getResult()
        ;
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
            ->orderBy('sr.createdAt', 'DESC')
        ;

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
                ->setParameter('date_end',   $date->format('Y-m-d 23:59:59'))
            ;
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
            ->getQueryBuilderByProjectAndFilters($project, $filters, 'COUNT(DISTINCT sr.id)')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
