<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;
use AppBundle\Repository\Traits\ProjectSortingTrait;

class RiskStrategyRepository extends BaseRepository
{
    use ProjectSortingTrait;

    public function findAllByProjectNullable(Project $project)
    {
        $qb = $this->createQueryBuilder('rs');

        $qb
            ->where(
                $qb
                    ->expr()
                    ->orX(
                        $qb->expr()->isNull('rs.project'),
                        $qb->expr()->eq('rs.project', $project->getId())
                    )
            )
        ;

        return $qb->getQuery()->getResult();
    }
}
