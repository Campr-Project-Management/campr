<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Risk;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Entity\Project;
use AppBundle\Repository\Traits\RiskStrategySortingTrait;
use AppBundle\Repository\Traits\RiskCategorySortingTrait;
use AppBundle\Repository\Traits\UserSortingTrait;

class RiskRepository extends BaseRepository
{
    use RiskStrategySortingTrait, RiskCategorySortingTrait, UserSortingTrait {
        RiskStrategySortingTrait::setOrder as setRiskStrategyOrder;
        RiskCategorySortingTrait::setOrder as setRiskCategoryOrder;
        UserSortingTrait::setOrder as setUserOrder;
    }

    public function getQueryBuilderByProject(Project $project)
    {
        return $this
            ->createQueryBuilder('r')
            ->where('r.project = :project')
            ->setParameter('project', $project)
        ;
    }

    /**
     * @param Project $project
     *
     * @return Risk
     */
    public function findTopByProject(Project $project)
    {
        return $this
            ->getQueryBuilderByProject($project)
            ->orderBy('r.priority', 'DESC')
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
        $costs = $this->getTotalPotentialCosts($project);
        $delay = $this->getTotalPotentialDelay($project);

        return [
            'costs' => $costs,
            'delay' => round($delay, 2),
            'averageData' => $averageData,
        ];
    }

    /**
     * @param Project $project
     *
     * @return float
     */
    public function getTotalPotentialCosts(Project $project)
    {
        $total = 0;
        $risks = $this
            ->getQueryBuilderByProject($project)
            ->getQuery()
            ->getResult()
        ;

        /** @var Risk $risk */
        foreach ($risks as $risk) {
            $total += $risk->getPotentialCost();
        }

        return $total;
    }

    /**
     * @param Project $project
     *
     * @return float
     */
    public function getTotalPotentialDelay(Project $project)
    {
        $hours = 0;
        /** @var Risk $risk */
        foreach ($project->getRisks() as $risk) {
            $hours += $risk->getPotentialDelayHours();
        }

        return $hours;
    }

    public function getAverageData($project)
    {
        return $this
            ->getQueryBuilderByProject($project)
            ->select('AVG(r.impact) as averageImpact, AVG(r.probability) as averageProbability')
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
        $this->setRiskStrategyOrder($orderBy, $qb);
        $this->setRiskCategoryOrder($orderBy, $qb);
        $this->setUserOrder($orderBy, $qb);
    }
}
