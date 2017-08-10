<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Cost;
use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackage;

class CostRepository extends BaseRepository
{
    public function getTotalBaseCostByPhase(Project $project, $type, $userIds = [])
    {
        $select = $type === Cost::TYPE_EXTERNAL
            ? 'SUM(c.rate * c.quantity) as base'
            : 'SUM(c.rate * c.quantity * c.duration) as base'
        ;
        $qb = $this
            ->createQueryBuilder('c')
            ->select($select)
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
