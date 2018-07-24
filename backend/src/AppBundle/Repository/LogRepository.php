<?php

namespace AppBundle\Repository;

use AppBundle\Entity\WorkPackage;
use Pagerfanta\Pagerfanta;

class LogRepository extends BaseRepository
{
    /**
     * @param WorkPackage $wp
     *
     * @return Pagerfanta
     */
    public function createWorkPackageHistoryPaginator(WorkPackage $wp)
    {
        $qb = $this
            ->createQueryBuilder('l')
            ->where('l.class = :class and l.objId = :objId')
            ->setParameter('class', WorkPackage::class)
            ->setParameter('objId', $wp->getId())
            ->orderBy('l.createdAt', 'DESC')
        ;

        return $this->getPaginator($qb);
    }
}
