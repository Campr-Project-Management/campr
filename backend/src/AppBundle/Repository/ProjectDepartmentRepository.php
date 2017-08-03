<?php

namespace AppBundle\Repository;

use AppBundle\Repository\Traits\ProjectSortingTrait;

class ProjectDepartmentRepository extends BaseRepository
{
    use ProjectSortingTrait;

    /**
     * Return project departments query.
     *
     * @return Query
     */
    public function getQueryFiltered($pageSize, $page)
    {
        return $this
            ->createQueryBuilder('pd')
            ->setFirstResult($pageSize * ($page - 1))
            ->setMaxResults($pageSize)
            ->getQuery()
        ;
    }
}
