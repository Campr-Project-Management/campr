<?php

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use AppBundle\Repository\Traits\ProjectStatusSortingTrait;
use AppBundle\Repository\Traits\CategorySortingTrait;
use AppBundle\Repository\Traits\ProjectPortfolioSortingTrait;

class ProjectRepository extends BaseRepository
{
    use CategorySortingTrait, ProjectStatusSortingTrait, ProjectPortfolioSortingTrait {
        CategorySortingTrait::setOrder as setCategoryOrder;
        ProjectStatusSortingTrait::setOrder as setProjectStatusOrder;
        ProjectPortfolioSortingTrait::setOrder as setProjectPortfolioOrder;
    }

    /**
     * @param string $name
     *
     * @return null|Project
     */
    public function findOneByName(string $name)
    {
        return $this->findOneBy([
            'name' => $name,
        ]);
    }

    /**
     * Return all projects for current user.
     *
     * @param User  $user
     * @param array $filters
     *
     * @return QueryBuilder
     */
    public function findByUserAndFilters(User $user, $filters = [], $select = null)
    {
        $qb = $this
            ->createQueryBuilder('p')
            ->leftJoin('p.projectUsers', 'pu')
            ->where('pu.user = :user')
            ->setParameter('user', $user)
        ;

        if (isset($filters['favorites'])) {
            $qb
                ->leftJoin('p.userFavorites', 'f')
                ->addOrderBy('f.id', 'DESC')
            ;
        }

        $qb
            ->addOrderBy('p.createdAt', 'DESC')
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
     * @param User  $user
     * @param array $filters
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

    /**
     * @param array        $orderBy
     * @param QueryBuilder $qb
     */
    public function setOrder(array &$orderBy, QueryBuilder $qb)
    {
        $this->setProjectStatusOrder($orderBy, $qb);
        $this->setCategoryOrder($orderBy, $qb);
        $this->setProjectPortfolioOrder($orderBy, $qb);
    }
}
