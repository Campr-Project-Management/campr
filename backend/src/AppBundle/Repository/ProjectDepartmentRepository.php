<?php

namespace AppBundle\Repository;

class ProjectDepartmentRepository extends BaseRepository
{
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
