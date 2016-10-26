<?php

namespace AppBundle\Repository;

class ProjectUserRepository extends BaseRepository
{
    /**
     * Used to retrieve information for data table.
     *
     * New fields to be added to WHERE clause where the $key should be searched for.
     *
     * @param string|null $key
     * @param string|null $field
     * @param string|null $order
     * @param int         $offset
     * @param int         $limit
     *
     * @return array
     */
    public function findByKeyAndField($key, $field, $order, $offset, $limit)
    {
        $qb = $this
            ->createQueryBuilder('q')
            ->join('q.user', 'u')
        ;

        if (isset($key)) {
            $qb
                ->where('u.username LIKE CONCAT(\'%\', :key, \'%\')')
                ->setParameter('key', $key)
            ;
        }

        if (isset($field) && isset($order)) {
            $qb->orderBy('q.'.$field, $order);
        }

        return $qb
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
