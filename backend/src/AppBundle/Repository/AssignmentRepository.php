<?php

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use AppBundle\Repository\Traits\WorkPackageSortingTrait;
use AppBundle\Repository\Traits\WorkPackageProjectWorkCostTypeSortingTrait;

class AssignmentRepository extends BaseRepository
{
    use WorkPackageSortingTrait, WorkPackageProjectWorkCostTypeSortingTrait {
        WorkPackageSortingTrait::setOrder as setWorkPackageOrder;
        WorkPackageProjectWorkCostTypeSortingTrait::setOrder as setWorkPackageProjectWorkCostTypeOrder;
    }

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
     * @param array        $orderBy
     * @param QueryBuilder $qb
     */
    public function setOrder(array &$orderBy, QueryBuilder $qb)
    {
        $this->setWorkPackageOrder($orderBy, $qb);
        $this->setWorkPackageProjectWorkCostTypeOrder($orderBy, $qb);
    }
}
