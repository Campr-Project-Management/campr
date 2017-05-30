<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;

class OpportunityRepository extends BaseRepository
{
    public function getQueryBuilderByProject(Project $project)
    {
        return $this
            ->createQueryBuilder('o')
            ->where('o.project = :project')
            ->setParameter('project', $project)
        ;
    }

    public function getStatsByProject(Project $project)
    {
        $qb = $this->getQueryBuilderByProject($project);
        $costSavings = $this->getTotalCostSavings($qb);
        $timeSavings = $this->getTotalTimeSavings($qb);

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
        ];
    }

    public function getTotalCostSavings($qb)
    {
        $qb
            ->select('o.currency, SUM(o.costSavings) as totalCost')
            ->groupBy('o.currency')
        ;

        return $qb->getQuery()->getArrayResult();
    }

    public function getTotalTimeSavings($qb)
    {
        $qb
            ->select('o.timeUnit, SUM(o.timeSavings) as totalTime')
            ->groupBy('o.timeUnit')
        ;

        return $qb->getQuery()->getArrayResult();
    }
}
