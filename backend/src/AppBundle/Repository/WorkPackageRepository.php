<?php

namespace AppBundle\Repository;

use AppBundle\Entity\ColorStatus;
use AppBundle\Entity\Cost;
use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\WorkPackageStatus;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Repository\Traits\WorkPackageSortingTrait;
use AppBundle\Repository\Traits\WorkPackageCategorySortingTrait;
use AppBundle\Repository\Traits\UserSortingTrait;
use Pagerfanta\Pagerfanta;

class WorkPackageRepository extends BaseRepository
{
    use WorkPackageSortingTrait, WorkPackageCategorySortingTrait, UserSortingTrait {
        WorkPackageSortingTrait::setOrder as setWorkPackageOrder;
        WorkPackageCategorySortingTrait::setOrder as setWorkPackageCategoryOrder;
        UserSortingTrait::setOrder as setUserOrder;
    }

    /**
     * Return all user workpackages filtered.
     *
     * @param User       $user
     * @param array      $criteria
     * @param null|mixed $select
     *
     * @return QueryBuilder
     */
    public function findUserFiltered(User $user, $criteria = [], $select = null)
    {
        $qb = $this
            ->createQueryBuilder('wp')
            ->where('wp.responsibility = :user')
            ->setParameter('user', $user)
        ;

        if ($select) {
            $qb->select($select);
        }

        if (isset($criteria['type'])) {
            $qb
                ->andWhere('wp.type = :type')
                ->setParameter('type', $criteria['type'])
            ;
        }

        if (isset($criteria['recent'])) {
            $startDate = new \DateTime('first day of this month');
            $endDate = new \DateTime('last day of this month');
            $qb
                ->andWhere('wp.createdAt >= :startDate')
                ->setParameter('startDate', $startDate)
                ->andWhere('wp.createdAt <= :endDate')
                ->setParameter('endDate', $endDate)
            ;
        }

        if (isset($criteria['project'])) {
            $qb
                ->andWhere('wp.project = :project')
                ->setParameter('project', $criteria['project'])
            ;
        }

        if (isset($criteria['status'])) {
            $qb
                ->andWhere('wp.colorStatus = :status')
                ->setParameter('status', $criteria['status'])
            ;
        }

        if (isset($criteria['milestone'])) {
            $qb
                ->andWhere('wp.isKeyMilestone = :milestone')
                ->setParameter('milestone', $criteria['milestone'])
            ;
        }
        if (isset($criteria['pageSize']) && isset($criteria['page'])) {
            $qb
                ->setFirstResult($criteria['pageSize'] * ($criteria['page'] - 1))
                ->setMaxResults($criteria['pageSize'])
            ;
        }

        return $qb;
    }

    /**
     * Counts the filtered projects.
     *
     * @param User  $user
     * @param array $criteria
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return int
     */
    public function countTotalByUserAndFilters(User $user, $criteria = [])
    {
        return (int) $this
            ->findUserFiltered($user, $criteria, 'COUNT(DISTINCT wp.id)')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * Return all project workpackages filtered.
     *
     * @param Project           $project
     * @param array             $criteria
     * @param WorkPackageStatus $workPackageStatus
     *
     * @return Query
     */
    public function findByProject(Project $project, $criteria = [], WorkPackageStatus $workPackageStatus = null)
    {
        $qb = $this
            ->createQueryBuilder('wp')
            ->innerJoin('wp.project', 'p')
            ->where('p.id = :project')
            ->setParameter('project', $project)
        ;

        if (isset($workPackageStatus)) {
            $qb
                ->andWhere('wp.workPackageStatus = :workPackageStatus')
                ->setParameter('workPackageStatus', $workPackageStatus)
            ;
        }

        if (isset($criteria['status'])) {
            $qb
                ->innerJoin('wp.workPackageStatus', 'wps')
                ->andWhere('wps.name = :statusName')
                ->setParameter('statusName', $criteria['status'])
            ;
        }

        if (isset($criteria['type'])) {
            $qb
                ->andWhere('wp.type = :type')
                ->setParameter('type', $criteria['type'])
            ;
        }

        if (isset($criteria['milestone'])) {
            $qb
                ->andWhere('wp.isKeyMilestone = :milestone')
                ->setParameter('milestone', $criteria['milestone'])
            ;
        }

        return $qb->getQuery();
    }

    /**
     * @param Project|null     $project
     * @param WorkPackage|null $workPackage
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return WorkPackage|null
     */
    public function findLastInsertedByParent(Project $project = null, WorkPackage $workPackage = null)
    {
        $qb = $this->createQueryBuilder('q');

        if (!$workPackage) {
            $qb->where('q.parent IS NULL');
        } else {
            $qb
                ->where('q.parent = :workPackage')
                ->setParameter('workPackage', $workPackage)
            ;
        }

        if (!$project) {
            $qb->andWhere('q.project IS NULL');
        } else {
            $qb
                ->andWhere('q.project = :project')
                ->setParameter('project', $project)
            ;
        }

        return $qb
            ->orderBy('q.puid', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findMaxPuid(Project $project = null)
    {
        $qb = $this
            ->createQueryBuilder('q')
            ->select('MAX(q.puid)')
        ;

        if (!$project) {
            $qb->andWhere('q.project IS NULL');
        } else {
            $qb
                ->andWhere('q.project = :project')
                ->setParameter('project', $project)
            ;
        }

        return (int) $qb
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function findPhasesByProject(Project $project)
    {
        return $this->findBy(
            [
                'project' => $project,
                'type' => WorkPackage::TYPE_PHASE,
            ],
            [
                'puid' => 'ASC',
            ]
        );
    }

    public function countPhasesByProject(Project $project)
    {
        $qb = $this
            ->createQueryBuilder('wp')
            ->select('COUNT(DISTINCT wp.id)')
        ;

        $qb->where(
            $qb->expr()->eq(
                'wp.project',
                $project->getId()
            )
        );

        $qb->andWhere(
            $qb->expr()->eq(
                'wp.type',
                WorkPackage::TYPE_PHASE
            )
        );

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function findMilestonesByProject(Project $project)
    {
        return $this->findBy(
            [
                'project' => $project,
                'type' => WorkPackage::TYPE_MILESTONE,
            ],
            [
                'puid' => 'ASC',
            ]
        );
    }

    public function countMilestonesByProject(Project $project)
    {
        $qb = $this
            ->createQueryBuilder('wp')
            ->select('COUNT(DISTINCT wp.id)')
        ;

        $qb->where(
            $qb->expr()->eq(
                'wp.project',
                $project->getId()
            )
        );

        $qb->andWhere(
            $qb->expr()->eq(
                'wp.type',
                WorkPackage::TYPE_MILESTONE
            )
        );

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function findMilestonesByPhase(WorkPackage $phase)
    {
        return $this->findBy(
            [
                'project' => $phase->getProject(),
                'type' => WorkPackage::TYPE_MILESTONE,
            ],
            [
                'puid' => 'ASC',
            ]
        );
    }

    public function findTasksByProject(
        Project $project,
        $orderBy = [],
        $limit = null,
        $offset = null
    ) {
        if (!$orderBy) {
            $orderBy = [
                'puid' => 'ASC',
            ];
        }

        return $this->findBy(
            [
                'project' => $project,
                'type' => WorkPackage::TYPE_TASK,
            ],
            $orderBy,
            $limit,
            $offset
        );
    }

    public function countTasksByProject(Project $project)
    {
        $qb = $this
            ->createQueryBuilder('wp')
            ->select('COUNT(DISTINCT wp.id)')
        ;

        $qb->where(
            $qb->expr()->eq(
                'wp.project',
                $project->getId()
            )
        );

        $qb->andWhere(
            $qb->expr()->eq(
                'wp.type',
                WorkPackage::TYPE_TASK
            )
        );

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function findTasksByMilestone(
        WorkPackage $milestone,
        $orderBy = [],
        $limit = null,
        $offset = null
    ) {
        if (!$orderBy) {
            $orderBy = [
                'puid' => 'ASC',
            ];
        }

        return $this->findBy(
            [
                'project' => $milestone->getProject(),
                'milestone' => $milestone,
                'type' => WorkPackage::TYPE_TASK,
            ],
            $orderBy,
            $limit,
            $offset
        );
    }

    public function countTasksByMilestone(WorkPackage $milestone)
    {
        $qb = $this
            ->createQueryBuilder('wp')
            ->select('COUNT(DISTINCT wp.id)')
        ;

        $qb->where(
            $qb->expr()->eq(
                'wp.milestone',
                $milestone->getId()
            )
        );

        $qb->andWhere(
            $qb->expr()->eq(
                'wp.type',
                WorkPackage::TYPE_TASK
            )
        );

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Return all project workpackages filtered and sorted in a custom order of the status.
     *
     * @param Project $project
     * @param array   $criteria
     *
     * @return Query
     */
    public function getQueryByProjectAndFiltersSortedByStatus(Project $project, $criteria = [])
    {
        $qBuilder = $this->getQueryBuilderByProjectAndFilters($project, $criteria);

        if (isset($criteria['isGrid'])) {
            $qBuilder->addSelect('(CASE
                    WHEN wp.workPackageStatus = :statusOngoing THEN 0
                    WHEN wp.workPackageStatus = :statusPending THEN 1
                    WHEN wp.workPackageStatus = :statusClosed THEN 2
                    ELSE 3
                END ) AS  HIDDEN tempFieldForSorting'
            );
            $qBuilder
                ->setParameter('statusOngoing', WorkPackageStatus::ONGOING)
                ->setParameter('statusPending', WorkPackageStatus::PENDING)
                ->setParameter('statusClosed', WorkPackageStatus::CLOSED)
            ;

            $qBuilder->addOrderBy('tempFieldForSorting', 'ASC');
            $qBuilder->addOrderBy('wp.actualStartAt', 'ASC');
        }

        return $qBuilder->getQuery();
    }

    /**
     * @param Project $project
     * @param array   $sorting
     * @param array   $criteria
     *
     * @return Pagerfanta
     */
    public function createGridViewPaginator(Project $project, array $criteria = [], array $sorting = [])
    {
        $qb = $this->createQueryBuilder('o');

        $this
            ->addNotSubtaskWhere($qb)
            ->innerJoin('o.project', 'p')
            ->andWhere('p.id = :project')
            ->setParameter('project', $project)
            ->addSelect(
                '(CASE
                    WHEN o.workPackageStatus = :statusOngoing THEN 0
                    WHEN o.workPackageStatus = :statusPending THEN 1
                    WHEN o.workPackageStatus = :statusClosed THEN 2
                    ELSE 3
                END) AS HIDDEN tempFieldForSorting'
            )
            ->setParameter('statusOngoing', WorkPackageStatus::ONGOING)
            ->setParameter('statusPending', WorkPackageStatus::PENDING)
            ->setParameter('statusClosed', WorkPackageStatus::CLOSED)
            ->addOrderBy('tempFieldForSorting', 'ASC')
        ;

        $this->applyCriteria($qb, $criteria);
        $this->applySorting($qb, array_merge($sorting, ['actualStartAt' => 'asc']));

        return $this->getPaginator($qb);
    }

    /**
     * @param Project $project
     * @param array   $criteria
     * @param array   $sorting
     *
     * @return Pagerfanta
     */
    public function createBoardViewPaginator(Project $project, array $criteria = [], array $sorting = []): Pagerfanta
    {
        $qb = $this->createQueryBuilder('o');

        $this
            ->addNotSubtaskWhere($qb)
            ->innerJoin('o.project', 'p')
            ->andWhere('p.id = :project')
            ->setParameter('project', $project)
        ;

        $this->applyCriteria($qb, $criteria);
        $this->applySorting($qb, $sorting);

        return $this->getPaginator($qb);
    }

    /**
     * Return all project workpackages filtered.
     *
     * @param Project $project
     * @param array   $criteria
     * @param array   $select
     *
     * @return Query
     */
    public function getQueryByProjectAndFilters(Project $project, $criteria = [], $select = null)
    {
        return $this
            ->getQueryBuilderByProjectAndFilters($project, $criteria, $select)
            ->getQuery()
        ;
    }

    /**
     * counts the filtered workpackages.
     *
     * @param Project $project
     * @param array   $criteria
     *
     * @return int
     */
    public function countTotalByProjectAndFilters(Project $project, $criteria = [])
    {
        return (int) $this
            ->getQueryBuilderByProjectAndFilters($project, $criteria, 'COUNT(DISTINCT wp.id)')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * Average progress value.
     *
     * @param Project $project
     * @param array   $criteria
     *
     * @return int
     */
    public function averageProgressByProjectAndFilters(Project $project, $criteria = [])
    {
        return (float) $this
            ->getQueryBuilderByProjectAndFilters($project, $criteria, 'AVG(wp.progress)')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * Return the query builder for all project workpackages filtered.
     *
     * @param Project    $project
     * @param array      $criteria
     * @param null|mixed $select
     *
     * @return QueryBuilder
     */
    public function getQueryBuilderByProjectAndFilters(Project $project, $criteria = [], $select = null)
    {
        $qb = $this
            ->createQueryBuilder('wp')
            ->innerJoin('wp.project', 'p')
            ->where('p.id = :project')
            ->setParameter('project', $project)
        ;

        if ($select) {
            $qb->select($select);
        }

        if (isset($criteria['phase'])) {
            $qb
                ->andWhere('wp.phase = :phase')
                ->setParameter('phase', $criteria['phase'])
            ;
        }

        if (isset($criteria['status'])) {
            $qb
                ->andWhere('wp.workPackageStatus = :workPackageStatus')
                ->setParameter('workPackageStatus', $criteria['status'])
            ;
        }

        if (isset($criteria['searchString'])) {
            $qb
                ->andWhere('wp.name LIKE :searchString')
                ->setParameter('searchString', '%'.$criteria['searchString'].'%')
            ;
        }

        if (isset($criteria['type'])) {
            if (WorkPackage::TYPE_TASK == $criteria['type']) {
                $qb
                    ->andWhere('wp.type IN (:type)')
                    ->setParameter('type', [$criteria['type'], WorkPackage::TYPE_TUTORIAL])
                ;
            } else {
                $qb
                    ->andWhere('wp.type = :type')
                    ->setParameter('type', $criteria['type'])
                ;
            }
        }

        if (isset($criteria['projectUser'])) {
            $qb
                ->andWhere('wp.responsibility = :projectUser')
                ->setParameter('projectUser', $criteria['projectUser'])
            ;
        }

        if (isset($criteria['colorStatus'])) {
            $qb
                ->andWhere('wp.colorStatus = :colorStatus')
                ->setParameter('colorStatus', $criteria['colorStatus'])
            ;
        }

        if (isset($criteria['isKeyMilestone'])) {
            $qb->andWhere($qb->expr()->eq('wp.isKeyMilestone', $criteria['isKeyMilestone']));
        }

        if (isset($criteria['startDate'])) {
            $qb
                ->andWhere('wp.scheduledStartAt >= :startDate')
                ->setParameter('startDate', $criteria['startDate'])
            ;
        }

        if (isset($criteria['endDate'])) {
            $qb
                ->andWhere('wp.scheduledFinishAt <= :endDate')
                ->setParameter('endDate', $criteria['endDate'])
            ;
        }

        if (isset($criteria['dueDate'])) {
            $qb
                ->andWhere('wp.scheduledFinishAt = :dueDate')
                ->setParameter('dueDate', $criteria['dueDate'])
            ;
        }

        if (isset($criteria['orderBy']) && isset($criteria['order'])) {
            $qb->orderBy('wp.'.$criteria['orderBy'], $criteria['order']);

            if (isset($criteria['excludeNullValuesFromOrderBy']) && true === $criteria['excludeNullValuesFromOrderBy']) {
                $qb->andWhere('wp.'.$criteria['orderBy'].' is NOT NULL');
            }
        }

        if (isset($criteria['pageSize']) && isset($criteria['page'])) {
            $qb
                ->setFirstResult($criteria['pageSize'] * ($criteria['page'] - 1))
                ->setMaxResults($criteria['pageSize']);
        }

        return $qb;
    }

    public function getScheduleForProjectSchedule(Project $project)
    {
        $baseStart = $this
            ->getQueryByProjectAndFilters($project, ['orderBy' => 'scheduledStartAt', 'order' => 'ASC', 'excludeNullValuesFromOrderBy' => true])
            ->setMaxResults(1)
            ->getResult()
        ;
        $baseFinish = $this
            ->getQueryByProjectAndFilters($project, ['orderBy' => 'scheduledFinishAt', 'order' => 'DESC'])
            ->setMaxResults(1)
            ->getResult()
        ;
        $forecastStart = $this
            ->getQueryByProjectAndFilters($project, ['orderBy' => 'forecastStartAt', 'order' => 'ASC', 'excludeNullValuesFromOrderBy' => true])
            ->setMaxResults(1)
            ->getResult()
        ;
        $forecastFinish = $this
            ->getQueryByProjectAndFilters($project, ['orderBy' => 'forecastFinishAt', 'order' => 'DESC'])
            ->setMaxResults(1)
            ->getResult()
        ;
        $actualStart = $this
            ->getQueryByProjectAndFilters($project, ['orderBy' => 'actualStartAt', 'order' => 'ASC', 'excludeNullValuesFromOrderBy' => true])
            ->setMaxResults(1)
            ->getResult()
        ;
        $actualFinish = $this
            ->getQueryByProjectAndFilters($project, ['orderBy' => 'actualFinishAt', 'order' => 'DESC'])
            ->setMaxResults(1)
            ->getResult()
        ;

        return [
            'base_start' => !empty($baseStart) ? $baseStart[0] : null,
            'base_finish' => !empty($baseFinish) ? $baseFinish[0] : null,
            'forecast_start' => !empty($forecastStart) ? $forecastStart[0] : null,
            'forecast_finish' => !empty($forecastFinish) ? $forecastFinish[0] : null,
            'actual_start' => !empty($actualStart) ? $actualStart[0] : null,
            'actual_finish' => !empty($actualFinish) ? $actualFinish[0] : null,
        ];
    }

    /**
     * counts all workpackages for a give type.
     *
     * @param int $type
     *
     * @return int
     */
    public function countTotalByTypeProjectAndStatus($type, Project $project = null, WorkPackageStatus $status = null, ColorStatus $colorStatus = null)
    {
        $qb = $this
            ->createQueryBuilder('wp')
            ->select('COUNT(wp.id)')
            ->andWhere('wp.type = :type')
            ->setParameter('type', $type)
        ;

        if ($project) {
            $qb
                ->andWhere('wp.project = :project')
                ->setParameter('project', $project)
            ;
        }

        if ($status) {
            $qb
                ->andWhere('wp.workPackageStatus = :status')
                ->setParameter('status', $status)
            ;
        }

        if ($colorStatus) {
            $qb
                ->andWhere('wp.colorStatus = :colorStatus')
                ->setParameter('colorStatus', $colorStatus)
            ;
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param Project $project
     * @param $type
     * @param array $userIds
     *
     * @return array
     */
    public function getTotalActualCostsByPhase(Project $project, $type, $userIds = [])
    {
        $qb = $this->getQueryBuilderByProjectAndFilters($project, ['type' => WorkPackage::TYPE_TASK]);

        if (Cost::TYPE_EXTERNAL === intval($type)) {
            $qb->select('SUM(wp.externalActualCost) as actual, SUM(wp.externalForecastCost) as forecast');
        } elseif (Cost::TYPE_INTERNAL === intval($type)) {
            $qb->select('SUM(wp.internalActualCost) as actual, SUM(wp.internalForecastCost) as forecast');
        }

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

    public function getTotalExternalInternalCosts(Project $project)
    {
        $qb = $this->getQueryBuilderByProjectAndFilters($project, ['type' => WorkPackage::TYPE_TASK]);
        $selectInternal = 'SUM(wp.internalActualCost) as actual, SUM(wp.internalForecastCost) as forecast';
        $selectExternal = 'SUM(wp.externalActualCost) as actual, SUM(wp.externalForecastCost) as forecast';

        $internalResult = $qb->select($selectInternal)->getQuery()->getSingleResult();
        $externalResult = $qb->select($selectExternal)->getQuery()->getSingleResult();

        return [
            'forecast' => (int) $internalResult['forecast'] + (int) $externalResult['forecast'],
            'actual' => (int) $internalResult['actual'] + (int) $externalResult['actual'],
        ];
    }

    /**
     * @param array        $orderBy
     * @param QueryBuilder $qb
     */
    public function setOrder(array &$orderBy, QueryBuilder $qb)
    {
        $this->setWorkPackageOrder($orderBy, $qb);
        $this->setWorkPackageCategoryOrder($orderBy, $qb);
        $this->setUserOrder($orderBy, $qb);
    }

    /**
     * Counts the Uncompleted tasks of a phase.
     *
     * @param WorkPackage $phase
     *
     * @return int
     */
    public function countUncompletedTasksByPhase(WorkPackage $phase)
    {
        return (int) $this
            ->createQueryBuilder('wp')
            ->select('COUNT(DISTINCT wp.id)')
            ->where('wp.phase = :phase')
            ->andWhere('wp.type = '.WorkPackage::TYPE_TASK)
            ->andWhere('wp.workPackageStatus != '.WorkPackageStatus::COMPLETED)
            ->setParameter('phase', $phase)
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * Counts the Uncompleted tasks of a milestone.
     *
     * @param WorkPackage $milestone
     *
     * @return int
     */
    public function countUncompletedTasksByMilestone(WorkPackage $milestone)
    {
        return (int) $this
            ->createQueryBuilder('wp')
            ->select('COUNT(DISTINCT wp.id)')
            ->where('wp.milestone = :milestone')
            ->andWhere('wp.type = '.WorkPackage::TYPE_TASK)
            ->andWhere('wp.workPackageStatus != '.WorkPackageStatus::COMPLETED)
            ->setParameter('milestone', $milestone)
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param QueryBuilder $qb
     * @param array        $criteria
     */
    private function applyCriteria(QueryBuilder $qb, array $criteria = [])
    {
        if (isset($criteria['phase'])) {
            $qb
                ->andWhere('o.phase = :phase')
                ->setParameter('phase', $criteria['phase'])
            ;
        }

        if (isset($criteria['status'])) {
            $qb
                ->andWhere('o.workPackageStatus = :workPackageStatus')
                ->setParameter('workPackageStatus', $criteria['status'])
            ;
        }

        if (isset($criteria['searchString'])) {
            $qb
                ->andWhere('o.name LIKE :searchString')
                ->setParameter('searchString', '%'.$criteria['searchString'].'%')
            ;
        }

        if (isset($criteria['type'])) {
            if (WorkPackage::TYPE_TASK == $criteria['type']) {
                $qb
                    ->andWhere('o.type IN (:type)')
                    ->setParameter('type', [$criteria['type'], WorkPackage::TYPE_TUTORIAL])
                ;
            } else {
                $qb
                    ->andWhere('o.type = :type')
                    ->setParameter('type', $criteria['type'])
                ;
            }
        }

        if (isset($criteria['projectUser'])) {
            $qb
                ->andWhere('o.responsibility = :projectUser')
                ->setParameter('projectUser', $criteria['projectUser'])
            ;
        }

        if (isset($criteria['colorStatus'])) {
            $qb
                ->andWhere('o.colorStatus = :colorStatus')
                ->setParameter('colorStatus', $criteria['colorStatus'])
            ;
        }

        if (isset($criteria['isKeyMilestone'])) {
            $qb->andWhere($qb->expr()->eq('wp.isKeyMilestone', $criteria['isKeyMilestone']));
        }

        if (isset($criteria['startDate'])) {
            $qb
                ->andWhere('o.scheduledStartAt >= :startDate')
                ->setParameter('startDate', $criteria['startDate'])
            ;
        }

        if (isset($criteria['endDate'])) {
            $qb
                ->andWhere('o.scheduledFinishAt <= :endDate')
                ->setParameter('endDate', $criteria['endDate'])
            ;
        }

        if (isset($criteria['dueDate'])) {
            $qb
                ->andWhere('o.scheduledFinishAt = :dueDate')
                ->setParameter('dueDate', $criteria['dueDate'])
            ;
        }
    }

    /**
     * @param QueryBuilder $qb
     *
     * @return QueryBuilder
     */
    private function addNotSubtaskWhere(QueryBuilder $qb)
    {
        $expr = $qb->expr();
        $qb->andWhere(
            $expr->orX(
                $expr->neq('o.type', WorkPackage::TYPE_TASK),
                $expr->andX(
                    $expr->eq('o.type', WorkPackage::TYPE_TASK),
                    $expr->isNull('o.parent')
                )
            )
        );

        return $qb;
    }
}
