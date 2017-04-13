<?php

namespace AppBundle\Repository;

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
}
