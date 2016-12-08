<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class BaseRepository
 * Represents the base class for all repository classes.
 */
abstract class BaseRepository extends EntityRepository
{
    /**
     * Finds entities based on criteria, order and limit.
     *
     * @param array      $criteria
     * @param array|null $orderBy
     * @param null       $limit
     * @param null       $offset
     *
     * @return array
     */
    public function findByWithLike(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $qb = $this->createQueryBuilder('q');

        foreach ($criteria as $key => $value) {
            if (empty($value)) {
                continue;
            }

            if ($key === 'findIn') {
                foreach ($criteria[$key] as $column => $vals) {
                    $qb
                        ->andWhere($qb->expr()->in('q.'.$column, ':vals'))
                        ->setParameter('vals', $vals)
                    ;
                }

                continue;
            }

            $qb->andWhere(
                $qb->expr()->like(
                    'q.'.$key,
                    $qb->expr()->literal('%'.$value.'%')
                )
            );
        }

        if ($orderBy) {
            foreach ($orderBy as $key => $value) {
                $qb->orderBy('q.'.$key, $value);
            }
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        if ($offset) {
            $qb->setFirstResult($offset);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Counts total number of entities.
     *
     * @return mixed
     */
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
