<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;

class UnitRepository extends BaseRepository
{
    public function findMaxSequence(Project $project = null)
    {
        $qb = $this
            ->createQueryBuilder('u')
            ->select('MAX(u.sequence)')
        ;

        if ($project) {
            $qb
                ->andWhere('u.project', ':project')
                ->setParameter('project', $project)
            ;
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findByProject(Project $project, $includeGlobal = false)
    {
        $qb = $this->createQueryBuilder('u');
        if ($includeGlobal) {
            $qb->where(
                $qb->expr()->orX(
                    $qb
                        ->expr()
                        ->eq('u.project', $project->getId()),
                    $qb->expr()->isNull('u.project')
                )
            );
        } else {
            $qb->where(
                $qb
                    ->expr()
                    ->eq('u.project', $project->getId())
            );
        }

        return $qb->getQuery()->getResult();
    }
}
