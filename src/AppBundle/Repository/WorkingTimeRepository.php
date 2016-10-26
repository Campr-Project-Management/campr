<?php

namespace AppBundle\Repository;

class WorkingTimeRepository extends BaseRepository
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
        $qb = $this->createQueryBuilder('q');

        if (isset($key)) {
            $qb
                ->innerJoin('q.day', 'd')
                ->innerJoin('d.calendar', 'c')
                ->where('c.name LIKE CONCAT(\'%\', :key, \'%\')')
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
