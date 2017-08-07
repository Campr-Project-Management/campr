<?php

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Gedmo\Sortable\Entity\Repository\SortableRepository;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class BaseRepository
 * Represents the base class for all repository classes.
 */
abstract class BaseRepository extends SortableRepository
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

        $this->setOrder($orderBy, $qb);

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

    /**
     * @param ParameterBag $filters
     * @param string       $param
     * @param int|null     $default
     * @param int|null     $forceMinimum
     *
     * @return int
     */
    protected function getIntParam(ParameterBag $filters, string $param, int $default = null, int $forceMinimum = null): int
    {
        $out = $filters->getInt($param, $default);

        if ($forceMinimum !== null && $out < $forceMinimum) {
            $out = $forceMinimum;
        }

        return $out;
    }

    /**
     * @param array        $orderBy
     * @param QueryBuilder $qb
     */
    protected function setOrder(array &$orderBy, QueryBuilder $qb)
    {
        if ($orderBy) {
            foreach ($orderBy as $key => $value) {
                $qb->orderBy('q.'.$key, $value);
            }
        }
    }
}
