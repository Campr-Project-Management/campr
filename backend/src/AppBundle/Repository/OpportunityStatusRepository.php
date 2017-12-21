<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;
use AppBundle\Repository\Traits\ProjectSortingTrait;

class OpportunityStatusRepository extends BaseRepository
{
    use ProjectSortingTrait;

    public function findAllByProjectNullable(Project $project)
    {
        $qb = $this->createQueryBuilder('os');

        $qb
            ->where(
                $qb
                    ->expr()
                    ->orX(
                        $qb->expr()->isNull('os.project'),
                        $qb->expr()->eq('os.project', $project->getId())
                    )
            )
        ;

        return $qb->getQuery()->getResult();
    }
}
