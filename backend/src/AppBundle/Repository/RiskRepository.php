<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;

class RiskRepository extends BaseRepository
{
    public function getQueryBuilderByProject(Project $project)
    {
        return $this
            ->createQueryBuilder('r')
            ->where('r.project = :project')
            ->setParameter('project', $project)
        ;
    }

    public function getStatsByProject(Project $project)
    {
        $qb = $this->getQueryBuilderByProject($project);
        $costs = $this->getTotalCosts($qb);
        $delays = $this->getTotalDelays($qb);

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
        ];
    }

    public function getTotalCosts($qb)
    {
        $qb
            ->select('r.currency, SUM(r.cost) as totalCost')
            ->groupBy('r.currency')
        ;

        return $qb->getQuery()->getArrayResult();
    }

    public function getTotalDelays($qb)
    {
        $qb
            ->select('r.delayUnit, SUM(r.delay) as totalDelay')
            ->groupBy('r.delayUnit')
        ;

        return $qb->getQuery()->getArrayResult();
    }
}
