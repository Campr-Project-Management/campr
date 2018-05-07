<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;
use AppBundle\Repository\Traits\UserSortingTrait;

class MeasureRepository extends BaseRepository
{
    use UserSortingTrait;

    /**
     * @param Project $project
     *
     * @return array
     */
    public function getStatsForRisk(Project $project)
    {
        $row = $this
            ->createQueryBuilder('m')
            ->select('COUNT(m.id) as measuresNumber, SUM(m.cost) as totalCost')
            ->innerJoin('m.risk', 'r')
            ->andWhere('r.project = :project')
            ->setParameter('project', $project)
            ->getQuery()
            ->getSingleResult()
        ;

        return [
            'measuresNumber' => (int) $row['measuresNumber'],
            'totalCost' => round($row['totalCost'], 2),
        ];
    }

    /**
     * @param Project $project
     *
     * @return array
     */
    public function getStatsForOpportunity(Project $project)
    {
        $row = $this->createQueryBuilder('m')
            ->select('COUNT(m.id) as measuresNumber, SUM(m.cost) as totalCost')
            ->innerJoin('m.opportunity', 'o')
            ->andWhere('o.project = :project')
            ->setParameter('project', $project)
            ->getQuery()
            ->getSingleResult()
        ;

        return [
            'measuresNumber' => (int) $row['measuresNumber'],
            'totalCost' => round($row['totalCost'], 2),
        ];
    }
}
