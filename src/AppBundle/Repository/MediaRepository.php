<?php

namespace AppBundle\Repository;

class MediaRepository extends BaseRepository
{
    public function findByWithLike(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $qb = $this
            ->createQueryBuilder('q')
            ->innerJoin('q.fileSystem', 'fs')
        ;

        foreach ($criteria as $key => $value) {
            if (empty($value)) {
                continue;
            }

            if ($key === 'findIn') {
                foreach ($criteria[$key] as $column => $vals) {
                    $qb
                        ->andWhere(
                            $qb->expr()->orX(
                                $qb->expr()->in('q.'.$column, ':vals'),
                                $qb->expr()->isNull('fs.project')
                            )
                        )
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
     * Count total media in related filesystems or where no project is associated.
     *
     * @param array $fsIds
     *
     * @return mixed
     */
    public function countTotalByFileSystem(array $fsIds)
    {
        $qb = $this
            ->createQueryBuilder('q')
            ->select('COUNT(q.id)')
            ->innerJoin('q.fileSystem', 'fs')
        ;

        return $qb
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->in('fs.id', ':ids'),
                    $qb->expr()->isNull('fs.project')
                )
            )
            ->setParameter('ids', $fsIds)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
