<?php

namespace AppBundle\Repository;

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

    public function getQueryBuilderByProject(Project $project)
    {
        return $this
            ->createQueryBuilder('o')
            ->where('o.project = :project')
            ->setParameter('project', $project)
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

    public function getStatsByProject(Project $project)
    {
        $gridValues = $this->getGridCount($project->getId());
        $averageData = $this->getAverageData($project);
        $costSavings = $this->getTotalCostSavings($project);
        $timeSavings = $this->getTotalTimeSavings($project);

        $daysTotal = 0;
        $hoursTotal = 0;
        foreach ($timeSavings as $timeSaving) {
            switch ($timeSaving['timeUnit']) {
                case 'choices.months':
                    $daysTotal += 30 * $timeSaving['totalTime'];
                    break;
                case 'choices.weeks':
                    $daysTotal += 7 * $timeSaving['totalTime'];
                    break;
                case 'choices.days':
                    $daysTotal += $timeSaving['totalTime'];
                    break;
                case 'choices.hours':
                    $daysTotal += intval($timeSaving['totalTime'] / 24);
                    $hoursTotal = intval($timeSaving['totalTime'] / 24) > 0
                        ? $timeSaving['totalTime'] - (intval($timeSaving['totalTime'] / 24) * 24)
                        : $timeSaving['totalTime']
                    ;
                    break;
            }
        }

        return [
            'costSavings' => $costSavings,
            'timeSavings' => [
                'days' => $daysTotal,
                'hours' => $hoursTotal,
            ],
            'averageData' => [
                'averageImpact' => round($averageData['averageImpact'], 2),
                'averageProbability' => round($averageData['averageProbability'], 2),
            ],
            'gridValues' => $gridValues,
        ];
    }

    public function getTotalCostSavings($project)
    {
        return $this
            ->getQueryBuilderByProject($project)
            ->select('o.currency, SUM(o.costSavings) as totalCost')
            ->groupBy('o.currency')
            ->getQuery()
            ->getArrayResult()
        ;
    }

    public function getTotalTimeSavings($project)
    {
        return $this
            ->getQueryBuilderByProject($project)
            ->select('o.timeUnit, SUM(o.timeSavings) as totalTime')
            ->groupBy('o.timeUnit')
            ->getQuery()
            ->getArrayResult()
        ;
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
