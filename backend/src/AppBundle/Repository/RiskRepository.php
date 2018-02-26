<?php

namespace AppBundle\Repository;

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

    const VERY_LOW = [0, 25];
    const LOW = [25, 50];
    const HIGH = [50, 75];
    const VERY_HIGH = [75, 100];

    public function getQueryBuilderByProject(Project $project)
    {
        return $this
            ->createQueryBuilder('r')
            ->where('r.project = :project')
            ->setParameter('project', $project)
        ;
    }

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

    public function findFiltered(Project $project, $filters = [])
    {
        $qb = $this->getQueryBuilderByProject($project);

        if (isset($filters['probability'])) {
            switch ($filters['probability']) {
                case 1:
                    $qb->andWhere('r.probability >= '.self::VERY_LOW[0]);
                    $qb->andWhere('r.probability <= '.self::VERY_LOW[1]);
                    break;
                case 2:
                    $qb->andWhere('r.probability > '.self::LOW[0]);
                    $qb->andWhere('r.probability <= '.self::LOW[1]);
                    break;
                case 3:
                    $qb->andWhere('r.probability > '.self::HIGH[0]);
                    $qb->andWhere('r.probability <= '.self::HIGH[1]);
                    break;
                case 4:
                    $qb->andWhere('r.probability > '.self::VERY_HIGH[0]);
                    $qb->andWhere('r.probability <= '.self::VERY_HIGH[1]);
                    break;
            }
        }
        if (isset($filters['impact'])) {
            switch ($filters['impact']) {
                case 1:
                    $qb->andWhere('r.impact >= '.self::VERY_LOW[0]);
                    $qb->andWhere('r.impact <= '.self::VERY_LOW[1]);
                    break;
                case 2:
                    $qb->andWhere('r.impact > '.self::LOW[0]);
                    $qb->andWhere('r.impact <= '.self::LOW[1]);
                    break;
                case 3:
                    $qb->andWhere('r.impact > '.self::HIGH[0]);
                    $qb->andWhere('r.impact <= '.self::HIGH[1]);
                    break;
                case 4:
                    $qb->andWhere('r.impact > '.self::VERY_HIGH[0]);
                    $qb->andWhere('r.impact <= '.self::VERY_HIGH[1]);
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
            FROM `risk`
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
        $costs = $this->getTotalPotentialCostSavings($project);
        $delays = $this->getTotalDelays($project);

        $daysTotal = 0;
        $hoursTotal = 0;
        foreach ($delays as $delay) {
            switch ($delay['delayUnit']) {
                case 'choices.months':
                    $daysTotal += 30 * $delay['totalDelay'];
                    break;
                case 'choices.weeks':
                    $daysTotal += 7 * $delay['totalDelay'];
                    break;
                case 'choices.days':
                    $daysTotal += $delay['totalDelay'];
                    break;
                case 'choices.hours':
                    $daysTotal += intval($delay['totalDelay'] / 24);
                    $hoursTotal = intval($delay['totalDelay'] / 24) > 0
                        ? $delay['totalDelay'] - (intval($delay['totalDelay'] / 24) * 24)
                        : $delay['totalDelay']
                    ;
                    break;
            }
        }

        return [
            'costs' => $costs,
            'delays' => [
                'days' => $daysTotal,
                'hours' => $hoursTotal,
            ],
            'averageData' => $averageData,
            'gridValues' => $gridValues,
        ];
    }

    /**
     * @param Project $project
     *
     * @return array
     */
    public function getTotalPotentialCostSavings(Project $project)
    {
        $data = [];
        $rows = $this
            ->getQueryBuilderByProject($project)
            ->select('r.currency, r.cost, r.probability')
            ->getQuery()
            ->getArrayResult()
        ;

        foreach ($rows as $row) {
            $currency = $row['currency'];
            $cost = round($row['cost'] * ($row['probability'] / 100), 4);
            if (empty($data[$currency])) {
                $data[$currency] = ['totalCost' => 0, 'currency' => $currency];
            }

            $data[$currency]['totalCost'] += $cost;
        }

        return array_values($data);
    }

    public function getTotalDelays($project)
    {
        return $this
            ->getQueryBuilderByProject($project)
            ->select('r.delayUnit, SUM(r.delay) as totalDelay')
            ->groupBy('r.delayUnit')
            ->getQuery()
            ->getArrayResult()
        ;
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
