<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;

class WorkPackageRepository extends BaseRepository
{
    /**
     * Return all user workpackages filtered.
     *
     * @param User  $user
     * @param array $filters
     *
     * @return array
     */
    public function findUserFiltered(User $user, $filters = [])
    {
        $qb = $this
            ->createQueryBuilder('wp')
            ->where('wp.responsibility = :user')
            ->setParameter('user', $user)
        ;

        if (isset($filters['recent'])) {
            $startDate = new \DateTime('first day of this month');
            $endDate = new \DateTime('last day of this month');
            $qb
                ->andWhere('wp.createdAt >= :startDate')
                ->setParameter('startDate', $startDate)
                ->andWhere('wp.createdAt <= :endDate')
                ->setParameter('endDate', $endDate)
            ;
        }

        if (isset($filters['project'])) {
            $qb
                ->innerJoin('wp.project', 'p')
                ->andWhere('p.name = :projectName')
                ->setParameter('projectName', $filters['project'])
            ;
        }

        if (isset($filters['status'])) {
            $qb
                ->innerJoin('wp.colorStatus', 'c')
                ->andWhere('c.name = :colorName')
                ->setParameter('colorName', $filters['status'])
            ;
        }

        if (isset($filters['schedule'])) {
            // TODO: Finish after we determine what is filtered here (schedule dates / schedule type)
        }

        if (isset($filters['milestone'])) {
            $qb
                ->andWhere('wp.isKeyMilestone = :milestone')
                ->setParameter('milestone', $filters['milestone'])
            ;
        }

        return $qb->getQuery();
    }
}
