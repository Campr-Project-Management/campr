<?php

namespace AppBundle\Repository;

class SubteamRepository extends BaseRepository
{
    /**
     * Return subteam query.
     *
     * @return Query
     */
    public function getQueryFiltered($filters)
    {
        $qb = $this->createQueryBuilder('s');

        if (isset($filters['project'])) {
            $qb
                ->where($qb->expr()->eq('s.project', ':project'))
                ->setParameter('project', $filters['project'])
            ;
        }

        if (isset($filters['parent'])) {
            if (!filter_var($filters['parent'], FILTER_VALIDATE_BOOLEAN)) {
                $qb->andWhere($qb->expr()->isNull('s.parent'));
            }
        }

        if (isset($filters['pageSize']) && isset($filters['page'])) {
            $qb
                ->setFirstResult($filters['pageSize'] * ($filters['page'] - 1))
                ->setMaxResults($filters['pageSize'])
            ;
        }

        return $qb->getQuery();
    }
}
