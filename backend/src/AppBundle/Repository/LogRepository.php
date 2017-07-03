<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class LogRepository extends EntityRepository
{
    /**
     * returns filtered history.
     *
     * @param string $class
     * @param int    $objId
     * @param array  $filters
     *
     * @return array
     */
    public function findByObjectAndFilters($class, $objId, $filters = [])
    {
        $qb = $this
            ->createQueryBuilder('l')
            ->where('l.class = :class')
            ->andWhere('l.objId = :objId')
            ->setParameter('class', $class)
            ->setParameter('objId', $objId)
            ->orderBy('l.id', 'DESC')
        ;

        if (isset($filters['pageSize']) && isset($filters['page'])) {
            $qb
                ->setFirstResult($filters['pageSize'] * ($filters['page'] - 1))
                ->setMaxResults($filters['pageSize'])
            ;
        }

        return $qb->getQuery()->getResult();
    }
}
