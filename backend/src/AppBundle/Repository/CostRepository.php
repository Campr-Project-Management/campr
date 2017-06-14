<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackage;

class CostRepository extends BaseRepository
{
    public function getTotalBaseCostByPhase(Project $project, $type, $userIds = [])
    {
        $qb = $this
            ->createQueryBuilder('c')
            ->select('SUM(c.rate * c.quantity * c.duration) as base')
            ->innerJoin('c.project', 'p')
            ->innerJoin('c.workPackage', 'wp')
            ->where('p.id = :project')
            ->andWhere('wp.type = :wpType')
            ->andWhere('c.type = :type')
            ->setParameters([
                'project' => $project,
                'wpType' => WorkPackage::TYPE_TASK,
                'type' => $type,
            ])
        ;

        if (!empty($userIds)) {
            $qb->andWhere(
                $qb->expr()->in('wp.responsibility', $userIds)
            );
        } else {
            $qb->addSelect('ph.name as phaseName');
            $qb->innerJoin('wp.phase', 'ph')->groupBy('wp.phase');
        }

        return $qb->getQuery()->getArrayResult();
    }
}
