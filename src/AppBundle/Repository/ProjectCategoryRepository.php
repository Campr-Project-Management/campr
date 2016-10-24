<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ProjectCategoryRepository extends EntityRepository
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
            $qb = $qb
                ->where('q.name LIKE CONCAT(\'%\', :key, \'%\')')
                ->setParameter('key', $key)
            ;
        }

        if (isset($field) && isset($order)) {
            $qb = $qb->orderBy('q.'.$field, $order);
        }

        return $qb
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    public function countTotal()
    {
        return $this
            ->createQueryBuilder('q')
            ->select('COUNT(q.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
