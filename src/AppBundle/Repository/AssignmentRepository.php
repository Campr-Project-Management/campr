<?php

namespace AppBundle\Repository;

class AssignmentRepository extends BaseRepository
{
    /**
     * Finds assignments based on criteria, order and limit.
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
        $qb = $this
            ->createQueryBuilder('q')
            ->leftJoin('q.workPackage', 'wp')
        ;

        foreach ($criteria as $key => $value) {
            if (empty($value)) {
                continue;
            }
            $qb->andWhere(
                $qb->expr()->like(
                    'wp.'.$key,
                    $qb->expr()->literal('%'.$value.'%')
                )
            );
        }

        if ($orderBy) {
            foreach ($orderBy as $key => $value) {
                $qb->orderBy('wp.'.$key, $value);
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
}
