<?php

namespace AppBundle\Repository;

class MediaRepository extends BaseRepository
{
    public function countTotalByFileSystem(array $fsIds)
    {
        $qb = $this
            ->createQueryBuilder('q')
            ->select('COUNT(q.id)')
        ;

        return $qb
            ->where($qb->expr()->in('q.fileSystem', $fsIds))
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
