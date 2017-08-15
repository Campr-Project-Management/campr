<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use AppBundle\Repository\Traits\CategorySortingTrait;

class ProjectRepository extends BaseRepository
{
    use CategorySortingTrait;

    /**
     * Return all projects for current user.
     *
     * @param User  $user
     * @param array $filters
     *
     * @return array
     */
    public function findByUserAndFilters(User $user, $filters = [], $select = null)
    {
        $qb = $this
            ->createQueryBuilder('p')
            ->leftJoin('p.projectUsers', 'pu')
            ->where('pu.user = :user')
            ->setParameter('user', $user)
            ->orderBy('p.createdAt', 'DESC')
        ;

        if ($select) {
            $qb->select($select);
        }

        if (isset($filters['status'])) {
            $qb->andWhere('p.status = :status')->setParameter('status', $filters['status']);
        }
        if (isset($filters['customer'])) {
            $qb->andWhere('p.company = :customer')->setParameter('customer', $filters['customer']);
        }
        if (isset($filters['programme'])) {
            $qb->andWhere('p.programme = :programme')->setParameter('programme', $filters['programme']);
        }
        if (isset($filters['pageSize']) && isset($filters['page'])) {
            $qb
                ->setFirstResult($filters['pageSize'] * ($filters['page'] - 1))
                ->setMaxResults($filters['pageSize'])
            ;
        }

        return $qb;
    }

    /**
     * Counts the filtered projects.
     *
     * @param Project $project
     * @param array   $filters
     *
     * @return int
     */
    public function countTotalByUserAndFilters(User $user, $filters = [])
    {
        return (int) $this
            ->findByUserAndFilters($user, $filters, 'COUNT(DISTINCT p.id)')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
