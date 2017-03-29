<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;

class WorkPackageProjectWorkCostTypeRepository extends BaseRepository
{
    public function findByProject(Project $project)
    {
        return $this
            ->createQueryBuilder('w')
            ->innerJoin('w.workPackage', 'wp')
            ->where('wp.project = :project')
            ->setParameter('project', $project)
            ->getQuery()
            ->getResult()
        ;
    }
}
