<?php

namespace AppBundle\Repository;

class MeasureRepository extends BaseRepository
{
    /**
     * @return array
     */
    public function getStatsForRisk()
    {
        $qb = $this->createQueryBuilder('m')
           ->select('COUNT(m.id) as measuresNumber, SUM(m.cost) as totalCost')
           ->where('m.risk is NOT NULL')
        ;

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * @return array
     */
    public function getStatsForOpportunity()
    {
        $qb = $this->createQueryBuilder('m')
            ->select('COUNT(m.id) as measuresNumber, SUM(m.cost) as totalCost')
            ->where('m.opportunity is NOT NULL')
        ;

        return $qb->getQuery()->getSingleResult();
    }
}
