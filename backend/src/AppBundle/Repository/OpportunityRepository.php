<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Opportunity;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Entity\Project;
use AppBundle\Repository\Traits\OpportunityStrategySortingTrait;
use AppBundle\Repository\Traits\OpportunityStatusSortingTrait;
use AppBundle\Repository\Traits\UserSortingTrait;

class OpportunityRepository extends BaseRepository
{
    use OpportunityStrategySortingTrait, OpportunityStatusSortingTrait, UserSortingTrait {
        OpportunityStrategySortingTrait::setOrder as setOpportunityStrategyOrder;
        OpportunityStatusSortingTrait::setOrder as setOpportunityStatusOrder;
        UserSortingTrait::setOrder as setUserOrder;
    }

    /**
     * @param Project $project
     *
     * @return QueryBuilder
     */
    public function getQueryBuilderByProject(Project $project)
    {
        return $this
            ->createQueryBuilder('o')
            ->where('o.project = :project')
            ->setParameter('project', $project)
        ;
    }

    public function findTopByProject(Project $project)
    {
        return $this
            ->getQueryBuilderByProject($project)
            ->orderBy('o.priority', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param Project $project
     *
     * @return array
     */
    public function getStatsByProject(Project $project)
    {
        $averageData = $this->getAverageData($project);
        $costSavings = $this->getTotalPotentialCostSavings($project);
        $timeSaving = $this->getTotalPotentialTimeSaving($project);

        return [
            'costSavings' => $costSavings,
            'timeSaving' => round($timeSaving, 2),
            'averageData' => [
                'averageImpact' => round($averageData['averageImpact'], 2),
                'averageProbability' => round($averageData['averageProbability'], 2),
            ],
        ];
    }

    /**
     * @param Project $project
     *
     * @return float
     */
    public function getTotalPotentialCostSavings(Project $project)
    {
        $total = 0;
        $opportunities = $this
            ->getQueryBuilderByProject($project)
            ->getQuery()
            ->getResult()
        ;

        /** @var Opportunity $opportunity */
        foreach ($opportunities as $opportunity) {
            $total += $opportunity->getPotentialCostSavings();
        }

        return $total;
    }

    /**
     * @param Project $project
     *
     * @return float
     */
    public function getTotalPotentialTimeSaving(Project $project)
    {
        $hours = 0;
        $opportunities = $this
            ->getQueryBuilderByProject($project)
            ->getQuery()
            ->getResult()
        ;

        /** @var Opportunity $opportunity */
        foreach ($opportunities as $opportunity) {
            $hours += $opportunity->getPotentialTimeSavingsHours();
        }

        return $hours;
    }

    public function getAverageData($project)
    {
        return $this
            ->getQueryBuilderByProject($project)
            ->select('AVG(o.impact) as averageImpact, AVG(o.probability) as averageProbability')
            ->getQuery()
            ->getSingleResult()
        ;
    }

    /**
     * @param array        $orderBy
     * @param QueryBuilder $qb
     */
    public function setOrder(array &$orderBy, QueryBuilder $qb)
    {
        $this->setOpportunityStrategyOrder($orderBy, $qb);
        $this->setOpportunityStatusOrder($orderBy, $qb);
        $this->setUserOrder($orderBy, $qb);
    }
}
