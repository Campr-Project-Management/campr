<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;
use AppBundle\Repository\Traits\ProjectSortingTrait;

class ResourceRepository extends BaseRepository
{
    use ProjectSortingTrait;

    /**
     * @param Project $project
     *
     * @return resource|null
     */
    public function findWithoutProjectUserOrWithShowInResourcesProjectUserByProject(Project $project)
    {
        $qb = $this->createQueryBuilder('r');
        $qb->leftJoin('r.projectUser', 'pu');
        $qb->where(
            $qb->expr()->eq(
                'r.project',
                $project->getId()
            )
        );
        $qb->andWhere(
            $qb
                ->expr()
                ->orX(
                    $qb->expr()->isNull('r.projectUser'),
                    $qb->expr()->eq('pu.showInResources',  true)
                )
            )
        ;

        return $qb->getQuery()->getResult();
    }
}
