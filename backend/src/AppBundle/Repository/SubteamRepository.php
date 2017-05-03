<?php

namespace AppBundle\Repository;

class SubteamRepository extends BaseRepository
{
    /**
     * Return subteam query.
     *
     * @return Query
     */
    public function getQueryFiltered($pageSize, $filters)
    {
        $qb = $this->createQueryBuilder('s');

        if (isset($filters['project'])) {
            $qb
                ->where($qb->expr()->eq('s.project', ':project'))
                ->setParameter('project', $filters['project'])
            ;
        }

        return $qb
            ->setFirstResult($pageSize * ($filters['page'] - 1))
            ->setMaxResults($pageSize)
            ->getQuery()
        ;
    }
}
