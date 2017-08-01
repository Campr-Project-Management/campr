<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;

class RiskRepository extends BaseRepository
{
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

    public function getStatsByProject(Project $project)
    {
        $gridValues = $this->getGridCount($project->getId());
        $averageData = $this->getAverageData($project);
        $costs = $this->getTotalCosts($project);
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

    public function getTotalCosts($project)
    {
        return $this
            ->getQueryBuilderByProject($project)
            ->select('r.currency, SUM(r.cost) as totalCost')
            ->groupBy('r.currency')
            ->getQuery()
            ->getArrayResult()
        ;
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
}
