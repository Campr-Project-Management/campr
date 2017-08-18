<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;

class MeasureRepository extends BaseRepository
{
    /**
     * @param Project $project
     *
     * @return array
     */
    public function getStatsForRisk(Project $project)
    {
        $qb = $this->createQueryBuilder('m')
            ->select('COUNT(m.id) as measuresNumber, SUM(m.cost) as totalCost')
            ->innerJoin('m.risk', 'r')
            ->where('m.risk is NOT NULL')
            ->andWhere('r.project = :project')
            ->setParameter('project', $project)
        ;

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * @param Project $project
     *
     * @return array
     */
    public function getStatsForOpportunity(Project $project)
    {
        $qb = $this->createQueryBuilder('m')
            ->select('COUNT(m.id) as measuresNumber, SUM(m.cost) as totalCost')
            ->innerJoin('m.opportunity', 'o')
            ->where('m.opportunity is NOT NULL')
            ->andWhere('o.project = :project')
            ->setParameter('project', $project)
        ;

        return $qb->getQuery()->getSingleResult();
    }
}
