<?php

namespace AppBundle\Repository;

use AppBundle\Repository\Traits\ProjectSortingTrait;

class ProjectStatusRepository extends BaseRepository
{
    use ProjectSortingTrait;

    public function findAllDisplayable()
    {
        $qb = $this->createQueryBuilder('ps');
        $qb->where(
            $qb
                ->expr()
                ->isNull(
                    'ps.project'
                )
        );
        $qb->andWhere(
            $qb
                ->expr()
                ->gte(
                    'ps.sequence',
                    0
                )
        );

        return $qb->getQuery()->getResult();
    }
}
