<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Repository\Traits\WorkPackageSortingTrait;
use AppBundle\Repository\Traits\ProjectWorkCostTypeSortingTrait;

class WorkPackageProjectWorkCostTypeRepository extends BaseRepository
{
    use WorkPackageSortingTrait, ProjectWorkCostTypeSortingTrait {
        WorkPackageSortingTrait::setOrder as setWorkPackageOrder;
        ProjectWorkCostTypeSortingTrait::setOrder as setProjectWorkCostTypeOrder;
    }

    /**
     * @param Project $project
     *
     * @return array
     */
    public function findByProject(Project $project)
    {
        return $this
            ->createQueryBuilder('w')
            ->innerJoin('w.workPackage', 'wp')
            ->where('wp.project = :project')
            ->setParameter('project', $project)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Project $project
     * @param bool    $isInternal
     *
     * @return mixed
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function costsByProject(Project $project, $isInternal = true)
    {
        $qb = $this
            ->createQueryBuilder('w')
            ->select('SUM(w.base) as base, SUM(w.change) as change,
                SUM(w.actual) as actual, SUM(w.remaining) as remaining , SUM(w.forecast) as forecast'
            )
            ->innerJoin('w.workPackage', 'wp')
            ->where('wp.project = :project')
            ->setParameter('project', $project)
        ;

        $qb = $isInternal ? $qb->andWhere($qb->expr()->isNull('w.externalId')) : $qb->andWhere($qb->expr()->isNotNull('w.externalId'));

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * @param array        $orderBy
     * @param QueryBuilder $qb
     */
    public function setOrder(array &$orderBy, QueryBuilder $qb)
    {
        $this->setWorkPackageOrder($orderBy, $qb);
        $this->setProjectWorkCostTypeOrder($orderBy, $qb);
    }
}
