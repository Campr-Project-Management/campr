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

    const VERY_LOW = [0, 25];
    const LOW = [25, 50];
    const HIGH = [50, 75];
    const VERY_HIGH = [75, 100];

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

    public function findFiltered(Project $project, $filters = [])
    {
        $qb = $this->getQueryBuilderByProject($project);

        if (isset($filters['probability'])) {
            switch ($filters['probability']) {
                case 1:
                    $qb->andWhere('o.probability >= '.self::VERY_LOW[0]);
                    $qb->andWhere('o.probability <= '.self::VERY_LOW[1]);
                    break;
                case 2:
                    $qb->andWhere('o.probability > '.self::LOW[0]);
                    $qb->andWhere('o.probability <= '.self::LOW[1]);
                    break;
                case 3:
                    $qb->andWhere('o.probability > '.self::HIGH[0]);
                    $qb->andWhere('o.probability <= '.self::HIGH[1]);
                    break;
                case 4:
                    $qb->andWhere('o.probability > '.self::VERY_HIGH[0]);
                    $qb->andWhere('o.probability <= '.self::VERY_HIGH[1]);
                    break;
            }
        }
        if (isset($filters['impact'])) {
            switch ($filters['impact']) {
                case 1:
                    $qb->andWhere('o.impact >= '.self::VERY_LOW[0]);
                    $qb->andWhere('o.impact <= '.self::VERY_LOW[1]);
                    break;
                case 2:
                    $qb->andWhere('o.impact > '.self::LOW[0]);
                    $qb->andWhere('o.impact <= '.self::LOW[1]);
                    break;
                case 3:
                    $qb->andWhere('o.impact > '.self::HIGH[0]);
                    $qb->andWhere('o.impact <= '.self::HIGH[1]);
                    break;
                case 4:
                    $qb->andWhere('o.impact > '.self::VERY_HIGH[0]);
                    $qb->andWhere('o.impact <= '.self::VERY_HIGH[1]);
                    break;
            }
        }

        return $qb->getQuery()->getResult();
    }

    public function getGridCount($projectId)
    {
        $sql = '
            SELECT
                CASE
                    WHEN probability <= :very_low_1 THEN 1
                    WHEN probability > :low_0 AND probability <= :low_1 THEN 2
                    WHEN probability > :high_0 AND probability <= :high_1 THEN 3
                    WHEN probability > :very_high_0 THEN 4
                    ELSE 1
                END as probability,
                CASE
                    WHEN impact <= :very_low_1 THEN 1
                    WHEN impact > :low_0 AND impact <= :low_1 THEN 2
                    WHEN impact > :high_0 AND impact <= :high_1 THEN 3
                    WHEN impact > :very_high_0 THEN 4
                    ELSE 1
                END as impact
            FROM `opportunity`
            WHERE project_id = :project_id
        ';

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute([
            ':very_low_1' => self::VERY_LOW[1],
            ':low_0' => self::LOW[0],
            ':low_1' => self::LOW[1],
            ':high_0' => self::HIGH[0],
            ':high_1' => self::HIGH[1],
            ':very_high_0' => self::VERY_HIGH[0],
            ':project_id' => $projectId,
        ]);
        $result = $stmt->fetchAll();
        $data = [];
        foreach ($result as $res) {
            $data[$res['probability'].'-'.$res['impact']] = array_key_exists($res['probability'].'-'.$res['impact'], $data)
                ? $data[$res['probability'].'-'.$res['impact']] + 1
                : 1
            ;
        }

        return $data;
    }

    /**
     * @param Project $project
     *
     * @return array
     */
    public function getStatsByProject(Project $project)
    {
        $gridValues = $this->getGridCount($project->getId());
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
            'gridValues' => $gridValues,
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
