<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;

class ProjectRepository extends BaseRepository
{
    /**
     * Return all projects for current user.
     *
     * @param User  $user
     * @param array $filters
     *
     * @return array
     */
    public function findByUserAndFilters(User $user, $filters = [])
    {
        $qb = $this
            ->createQueryBuilder('p')
            ->leftJoin('p.projectUsers', 'pu')
            ->where('pu.user = :user')
            ->setParameter('user', $user)
        ;

        return $qb->getQuery()->getResult();
    }
}
