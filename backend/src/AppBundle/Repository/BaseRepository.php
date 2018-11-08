<?php

namespace AppBundle\Repository;

use Component\Repository\RepositoryInterface;
use Doctrine\ORM\QueryBuilder;
use Gedmo\Sortable\Entity\Repository\SortableRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class BaseRepository
 * Represents the base class for all repository classes.
 */
abstract class BaseRepository extends SortableRepository implements RepositoryInterface
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
            if ('findIn' === $key) {
                foreach ($criteria[$key] as $column => $vals) {
                    $qb
                        ->andWhere($qb->expr()->in('q.'.$column, ':vals'))
                        ->setParameter('vals', $vals)
                    ;
                }

                continue;
            }

            if ('deleted' === $key) {
                if (false === $value) {
                    $qb->andWhere('q.deletedAt is null');
                } else {
                    $qb->andWhere('q.deletedAt is not null');
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

        if (null !== $forceMinimum && $out < $forceMinimum) {
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

    /**
     * @param QueryBuilder $queryBuilder
     *
     * @return Pagerfanta
     */
    protected function getPaginator(QueryBuilder $queryBuilder)
    {
        return new Pagerfanta(new DoctrineORMAdapter($queryBuilder, false, false));
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array        $sorting
     */
    protected function applySorting(QueryBuilder $queryBuilder, array $sorting = [])
    {
        foreach ($sorting as $property => $order) {
            if (!in_array($property, array_merge($this->_class->getAssociationNames(), $this->_class->getFieldNames()), true)) {
                continue;
            }
            if (!empty($order)) {
                $queryBuilder->addOrderBy($this->getPropertyName($property), $order);
            }
        }
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getPropertyName(string $name)
    {
        if (false === strpos($name, '.')) {
            return 'o'.'.'.$name;
        }

        return $name;
    }

    /**
     * @param object $entity
     */
    public function add($entity)
    {
        $this->_em->persist($entity);
        $this->_em->flush();
    }

    /**
     * @param object $entity
     */
    public function remove($entity)
    {
        if (null !== $this->find($entity->getId())) {
            $this->_em->remove($entity);
            $this->_em->flush();
        }
    }
}
