<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Cost;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackage;
use AppBundle\Repository\Traits\ProjectSortingTrait;
use AppBundle\Repository\Traits\WorkPackageSortingTrait;
use AppBundle\Repository\Traits\ResourceSortingTrait;

class CostRepository extends BaseRepository
{
    use ProjectSortingTrait, WorkPackageSortingTrait, ResourceSortingTrait {
        ProjectSortingTrait::setOrder as setProjectOrder;
        WorkPackageSortingTrait::setOrder as setWorkPackageOrder;
        ResourceSortingTrait::setOrder as setResourceOrder;
    }

    /**
     * @param Project $project
     * @param $type
     * @param array $userIds
     *
     * @return array
     */
    public function getTotalBaseCostByPhase(Project $project, $type, $userIds = [])
    {
        $select = $type === Cost::TYPE_EXTERNAL
            ? 'SUM(c.rate * c.quantity) as base'
            : 'SUM(c.rate * c.quantity * c.duration) as base'
        ;
        $qb = $this
            ->createQueryBuilder('c')
            ->select($select)
            ->innerJoin('c.project', 'p')
            ->innerJoin('c.workPackage', 'wp')
            ->where('p.id = :project')
            ->andWhere('wp.type = :wpType')
            ->andWhere('c.type = :type')
            ->setParameters([
                'project' => $project,
                'wpType' => WorkPackage::TYPE_TASK,
                'type' => $type,
            ])
        ;

        if (!empty($userIds)) {
            $qb->andWhere(
                $qb->expr()->in('wp.responsibility', $userIds)
            );
        } else {
            $qb->addSelect('ph.name as phaseName');
            $qb->innerJoin('wp.phase', 'ph')->groupBy('wp.phase');
        }

        return $qb->getQuery()->getArrayResult();
    }

    public function getTotalBaseCost(Project $project)
    {
        $selectInternal = 'SUM(c.rate * c.quantity * c.duration) as base';
        $selectExternal = 'SUM(c.rate * c.quantity) as base';

        $qb = $this
            ->createQueryBuilder('c')
            ->where('c.project = :project')
            ->setParameter('project', $project)
        ;

        $internalResult = $qb->select($selectInternal)->getQuery()->getSingleScalarResult();
        $externalResult = $qb->select($selectExternal)->getQuery()->getSingleScalarResult();

        return $internalResult + $externalResult;
    }

    /**
     * @param array        $orderBy
     * @param QueryBuilder $qb
     */
    public function setOrder(array &$orderBy, QueryBuilder $qb)
    {
        $this->setProjectOrder($orderBy, $qb);
        $this->setWorkPackageOrder($orderBy, $qb);
        $this->setResourceOrder($orderBy, $qb);
    }
}
