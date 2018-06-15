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
use Webmozart\Assert\Assert;

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

        if (isset($criteria['userRasci'])) {
            $qb
                ->leftJoin('wp.informedUsers', 'iu')
                ->leftJoin('wp.consultedUsers', 'cu')
                ->leftJoin('wp.supportUsers', 'su');
            $qb
                ->orWhere('wp.accountability = :user')
                ->orWhere('iu.id = :user')
                ->orWhere('cu.id = :user')
                ->orWhere('su.id = :user');
        }

        if (isset($criteria['project'])) {
            $qb
                ->andWhere('wp.project = :project')
                ->setParameter('project', $criteria['project'])
            ;
        }

        if (isset($criteria['colorStatus'])) {
            $qb
                ->andWhere('wp.colorStatus = :colorStatus')
                ->setParameter('colorStatus', $criteria['colorStatus'])
            ;
        }

        if (isset($criteria['status'])) {
            $qb
                ->andWhere('wp.workPackageStatus = :status')
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

        if (isset($criteria['userRasci'])) {
            // Exclude closed tasks
            $qb
                ->andWhere('wp.workPackageStatus != :workPackageStatus')
                ->setParameter('workPackageStatus', WorkPackageStatus::CLOSED);

            $statuses = array(
                WorkPackageStatus::ONGOING,
                WorkPackageStatus::PENDING,
                WorkPackageStatus::COMPLETED,
                WorkPackageStatus::OPEN,
            );
            $qb->addOrderBy('FIELD(wp.workPackageStatus, '.implode(',', $statuses).')');
            $qb->addOrderBy('wp.forecastStartAt', 'DESC');
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
                'type' => [WorkPackage::TYPE_TASK, WorkPackage::TYPE_TUTORIAL],
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
            $qb->expr()->orX(
                $qb->expr()->eq(
                    'wp.type',
                    WorkPackage::TYPE_TASK
                ),
                $qb->expr()->eq(
                    'wp.type',
                    WorkPackage::TYPE_TUTORIAL
                )
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
            $qBuilder->addSelect(
                '(CASE
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
    public function createBoardViewPaginator(Project $project, array $criteria = [], array $sorting = [])
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
     * @param Project $project
     *
     * @return float
     */
    public function getProjectProgress(Project $project)
    {
        $qb = $this->createQueryBuilder('o');
        $expr = $qb->expr();

        return (float) $qb
            ->select('AVG(o.progress)')
            ->innerJoin('o.project', 'p')
            ->where('p.id = :project and o.type = :type')
            ->andWhere($expr->isNull('o.parent'))
            ->setParameter('project', $project)
            ->setParameter('type', WorkPackage::TYPE_TASK)
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
     * @param Project $project
     *
     * @return int
     */
    public function getTotalOpenedCount(Project $project)
    {
        $qb = $this->createQueryBuilder('o');
        $expr = $qb->expr();

        return (int) $qb
            ->select('COUNT(o.id)')
            ->where('o.project = :project and o.type = :type and o.workPackageStatus = :status')
            ->andWhere($expr->isNull('o.parent'))
            ->setParameter('project', $project)
            ->setParameter('type', WorkPackage::TYPE_TASK)
            ->setParameter('status', WorkPackageStatus::OPEN)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param Project $project
     *
     * @return int
     */
    public function getTotalClosedCount(Project $project)
    {
        $qb = $this->createQueryBuilder('o');
        $expr = $qb->expr();

        return (int) $qb
            ->select('COUNT(o.id)')
            ->where('o.project = :project and o.type = :type and o.workPackageStatus = :status')
            ->andWhere($expr->isNull('o.parent'))
            ->setParameter('project', $project)
            ->setParameter('type', WorkPackage::TYPE_TASK)
            ->setParameter('status', WorkPackageStatus::CLOSED)
            ->getQuery()
            ->getSingleScalarResult()
        ;
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
            ->andWhere('wp.type = :type AND wp.parent IS NULL')
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
        $qb = $this->createQueryBuilder('o');
        $expr = $qb->expr();

        $qb = $qb
            ->innerJoin('o.project', 'p')
            ->where('p.id = :project and o.type = :type')
            ->andWhere($expr->isNull('o.parent'))
            ->setParameter('project', $project)
            ->setParameter('type', WorkPackage::TYPE_TASK)
        ;

        if (Cost::TYPE_EXTERNAL === intval($type)) {
            $qb->select('SUM(o.externalActualCost) as actual, SUM(o.externalForecastCost) as forecast');
        } elseif (Cost::TYPE_INTERNAL === intval($type)) {
            $qb->select('SUM(o.internalActualCost) as actual, SUM(o.internalForecastCost) as forecast');
        }

        if (!empty($userIds)) {
            $qb->andWhere(
                $qb->expr()->in('o.responsibility', $userIds)
            );
        } else {
            $qb
                ->addSelect('ph.name as phaseName')
                ->innerJoin('o.phase', 'ph')
                ->groupBy('o.phase')
            ;
        }

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param Project $project
     *
     * @return array
     */
    public function getTotalExternalInternalCosts(Project $project)
    {
        $qb = $this->createQueryBuilder('o');
        $expr = $qb->expr();

        $qb = $qb
            ->innerJoin('o.project', 'p')
            ->where('p.id = :project and o.type = :type')
            ->andWhere($expr->isNull('o.parent'))
            ->setParameter('project', $project)
            ->setParameter('type', WorkPackage::TYPE_TASK)
        ;

        $selectInternal = 'SUM(o.internalActualCost) as actual, SUM(o.internalForecastCost) as forecast';
        $selectExternal = 'SUM(o.externalActualCost) as actual, SUM(o.externalForecastCost) as forecast';

        $internalResult = $qb
            ->select($selectInternal)
            ->getQuery()
            ->getSingleResult()
        ;
        $externalResult = $qb
            ->select($selectExternal)
            ->getQuery()
            ->getSingleResult()
        ;

        return [
            'internal' => [
                'forecast' => (float) $internalResult['forecast'],
                'actual' => (float) $internalResult['actual'],
            ],
            'external' => [
                'forecast' => (float) $externalResult['forecast'],
                'actual' => (float) $externalResult['actual'],
            ],
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
     * @param Project $project
     *
     * @return array
     */
    public function getRasciList(Project $project)
    {
        $qb = $this->createQueryBuilder('o');

        $this->addNotSubtaskWhere($qb);

        return $qb
            ->andWhere('o.project = :project')
            ->setParameter('project', $project->getId())
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Project $project
     *
     * @return string|null
     */
    public function getProjectScheduledStartAt(Project $project)
    {
        $qb = $this->createQueryBuilder('o');
        $expr = $qb->expr();

        return $qb
            ->select('MIN(o.scheduledStartAt)')
            ->andWhere('o.type = :type and o.project = :project')
            ->andWhere($expr->isNotNull('o.scheduledStartAt'))
            ->andWhere($expr->isNull('o.parent'))
            ->setParameter('type', WorkPackage::TYPE_TASK)
            ->setParameter('project', $project)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param Project $project
     *
     * @return string|null
     */
    public function getProjectScheduledFinishAt(Project $project)
    {
        $createQueryBuilder = function () use ($project) {
            $qb = $this->createQueryBuilder('o');
            $expr = $qb->expr();

            return $qb
                ->andWhere('o.type = :type and o.project = :project')
                ->andWhere($expr->isNull('o.parent'))
                ->setParameter('project', $project)
                ->setParameter('type', WorkPackage::TYPE_TASK)
            ;
        };

        $qb = $createQueryBuilder();
        $expr = $qb->expr();

        $found = $createQueryBuilder()
            ->select('COUNT(o)')
            ->andWhere($expr->isNull('o.scheduledFinishAt'))
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;

        if ($found > 0) {
            return null;
        }

        return $createQueryBuilder()
            ->select('MAX(o.scheduledFinishAt)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param Project $project
     *
     * @return string|null
     */
    public function getProjectActualStartAt(Project $project)
    {
        $qb = $this->createQueryBuilder('o');
        $expr = $qb->expr();

        return $qb
            ->select('MIN(o.actualStartAt)')
            ->andWhere('o.type = :type and o.project = :project')
            ->andWhere($expr->isNotNull('o.actualStartAt'))
            ->andWhere($expr->isNull('o.parent'))
            ->setParameter('type', WorkPackage::TYPE_TASK)
            ->setParameter('project', $project)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param Project $project
     *
     * @return string|null
     */
    public function getProjectActualFinishAt(Project $project)
    {
        $createQueryBuilder = function () use ($project) {
            $qb = $this->createQueryBuilder('o');
            $expr = $qb->expr();

            return $qb
                ->andWhere('o.type = :type and o.project = :project')
                ->andWhere($expr->isNull('o.parent'))
                ->setParameter('project', $project)
                ->setParameter('type', WorkPackage::TYPE_TASK)
            ;
        };

        $qb = $createQueryBuilder();
        $expr = $qb->expr();

        $found = $createQueryBuilder()
            ->select('COUNT(o)')
            ->andWhere($expr->isNull('o.actualFinishAt'))
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;

        if ($found > 0) {
            return null;
        }

        return $createQueryBuilder()
            ->select('MAX(o.actualFinishAt)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param Project $project
     *
     * @return string|null
     */
    public function getProjectForecastStartAt(Project $project)
    {
        $qb = $this->createQueryBuilder('o');
        $expr = $qb->expr();

        return $qb
            ->select('MIN(o.forecastStartAt)')
            ->andWhere('o.type = :type and o.project = :project')
            ->andWhere($expr->isNotNull('o.forecastStartAt'))
            ->andWhere($expr->isNull('o.parent'))
            ->setParameter('type', WorkPackage::TYPE_TASK)
            ->setParameter('project', $project)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param Project $project
     *
     * @return string|null
     */
    public function getProjectForecastFinishAt(Project $project)
    {
        $createQueryBuilder = function () use ($project) {
            $qb = $this->createQueryBuilder('o');
            $expr = $qb->expr();

            return $this
                ->createQueryBuilder('o')
                ->andWhere('o.type = :type and o.project = :project')
                ->andWhere($expr->isNull('o.parent'))
                ->setParameter('project', $project)
                ->setParameter('type', WorkPackage::TYPE_TASK)
            ;
        };

        $qb = $createQueryBuilder();
        $expr = $qb->expr();

        $found = $createQueryBuilder()
            ->select('COUNT(o)')
            ->andWhere($expr->isNull('o.forecastFinishAt'))
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;

        if ($found > 0) {
            return null;
        }

        return $createQueryBuilder()
            ->select('MAX(o.forecastFinishAt)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param WorkPackage $workPackage
     *
     * @return \DateTime|null
     */
    public function getPhaseActualStartDate(WorkPackage $workPackage)
    {
        $qb = $this->createQueryBuilder('o');
        $expr = $qb->expr();

        return $qb
            ->select('MIN(o.actualStartAt)')
            ->andWhere('o.phase = :phase and o.type = :type')
            ->andWhere($expr->isNotNull('o.actualStartAt'))
            ->setParameter('phase', $workPackage->getId())
            ->setParameter('type', WorkPackage::TYPE_TASK)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param WorkPackage $workPackage
     *
     * @return \DateTime|null
     */
    public function getPhaseActualFinishDate(WorkPackage $workPackage)
    {
        $createQueryBuilder = function () use ($workPackage) {
            $qb = $this->createQueryBuilder('o');

            return $qb
                ->andWhere('o.phase = :phase and o.type = :type')
                ->setParameter('phase', $workPackage->getId())
                ->setParameter('type', WorkPackage::TYPE_TASK)
            ;
        };

        $qb = $createQueryBuilder();
        $expr = $qb->expr();

        $found = $createQueryBuilder()
            ->select('COUNT(o)')
            ->andWhere($expr->isNull('o.actualFinishAt'))
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;

        if ($found > 0) {
            return null;
        }

        return $createQueryBuilder()
            ->select('MAX(o.actualFinishAt)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param WorkPackage $workPackage
     *
     * @return \DateTime|null
     */
    public function getPhaseForecastStartDate(WorkPackage $workPackage)
    {
        $qb = $this->createQueryBuilder('o');
        $expr = $qb->expr();

        return $qb
            ->select('MIN(o.forecastStartAt)')
            ->andWhere('o.phase = :phase and o.type = :type')
            ->andWhere($expr->isNotNull('o.forecastStartAt'))
            ->setParameter('phase', $workPackage->getId())
            ->setParameter('type', WorkPackage::TYPE_TASK)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param WorkPackage $workPackage
     *
     * @return \DateTime|null
     */
    public function getPhaseForecastFinishDate(WorkPackage $workPackage)
    {
        return $this
            ->createQueryBuilder('o')
            ->select('MAX(o.forecastFinishAt)')
            ->andWhere('o.phase = :phase and o.type = :type')
            ->setParameter('phase', $workPackage->getId())
            ->setParameter('type', WorkPackage::TYPE_TASK)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param WorkPackage $workPackage
     *
     * @return \DateTime|null
     */
    public function getMilestoneActualStartDate(WorkPackage $workPackage)
    {
        $qb = $this->createQueryBuilder('o');
        $expr = $qb->expr();

        return $qb
            ->select('MIN(o.actualStartAt)')
            ->andWhere('o.milestone = :milestone and o.type = :type')
            ->andWhere($expr->isNotNull('o.actualStartAt'))
            ->setParameter('milestone', $workPackage->getId())
            ->setParameter('type', WorkPackage::TYPE_TASK)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param WorkPackage $workPackage
     *
     * @return \DateTime|null
     */
    public function getMilestoneActualFinishDate(WorkPackage $workPackage)
    {
        $createQueryBuilder = function () use ($workPackage) {
            $qb = $this->createQueryBuilder('o');

            return $qb
                ->andWhere('o.milestone = :milestone and o.type = :type')
                ->setParameter('milestone', $workPackage->getId())
                ->setParameter('type', WorkPackage::TYPE_TASK)
                ;
        };

        $qb = $createQueryBuilder();
        $expr = $qb->expr();

        $found = $createQueryBuilder()
            ->select('COUNT(o)')
            ->andWhere($expr->isNull('o.actualFinishAt'))
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;

        if ($found > 0) {
            return null;
        }

        return $createQueryBuilder()
            ->select('MAX(o.actualFinishAt)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param WorkPackage $workPackage
     *
     * @return \DateTime|null
     */
    public function getMilestoneForecastStartDate(WorkPackage $workPackage)
    {
        $qb = $this->createQueryBuilder('o');
        $expr = $qb->expr();

        return $qb
            ->select('MIN(o.forecastStartAt)')
            ->andWhere('o.milestone = :milestone and o.type = :type')
            ->andWhere($expr->isNotNull('o.forecastStartAt'))
            ->setParameter('milestone', $workPackage->getId())
            ->setParameter('type', WorkPackage::TYPE_TASK)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param WorkPackage $workPackage
     *
     * @return \DateTime|null
     */
    public function getMilestoneForecastFinishDate(WorkPackage $workPackage)
    {
        return $this
            ->createQueryBuilder('o')
            ->select('MAX(o.forecastFinishAt)')
            ->andWhere('o.milestone = :milestone and o.type = :type')
            ->setParameter('milestone', $workPackage->getId())
            ->setParameter('type', WorkPackage::TYPE_TASK)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param \DateTime $date
     *
     * @return array
     */
    public function findAllCreatedBefore(\DateTime $date)
    {
        $qb = $this->createQueryBuilder('o');

        $qb = $qb
            ->where('o.type = :type and o.createdAt <= :createdAt')
            ->setParameter('type', WorkPackage::TYPE_TUTORIAL)
            ->setParameter('createdAt', $date)
            ->getQuery()
        ;

        return $qb->getResult();
    }

    /**
     * @param Project $project
     *
     * @return array
     */
    public function getTrafficLightCount(Project $project)
    {
        $qb = $this->createQueryBuilder('o');
        $expr = $qb->expr();

        $qb
            ->select('COUNT(o.id)')
            ->innerJoin('o.colorStatus', 'cs')
            ->andWhere('o.type = :type and o.project = :project and cs.code = :code')
            ->andWhere($expr->isNotNull('o.colorStatus'))
            ->andWhere($expr->isNull('o.parent'))
            ->setParameter('type', WorkPackage::TYPE_TASK)
            ->setParameter('project', $project)
        ;

        $red = (int) $qb
            ->setParameter('code', 'red')
            ->getQuery()
            ->getSingleScalarResult()
        ;

        $green = (int) $qb
            ->setParameter('code', 'green')
            ->getQuery()
            ->getSingleScalarResult()
        ;

        $yellow = (int) $qb
            ->setParameter('code', 'yellow')
            ->getQuery()
            ->getSingleScalarResult()
        ;

        return [
            'red' => $red,
            'yellow' => $yellow,
            'green' => $green,
        ];
    }

    /**
     * @param QueryBuilder $qb
     * @param array        $criteria
     */
    private function applyCriteria(QueryBuilder $qb, array $criteria = [])
    {
        $expr = $qb->expr();
        if (isset($criteria['phase'])) {
            $qb
                ->andWhere('o.phase = :phase')
                ->setParameter('phase', $criteria['phase'])
            ;
        }

        if (!empty($criteria['status'])) {
            $qb
                ->andWhere('o.workPackageStatus = :workPackageStatus')
                ->setParameter('workPackageStatus', $criteria['status'])
            ;
        }

        if (!empty($criteria['searchString'])) {
            $qb->andWhere($expr->like('o.name', $expr->literal(sprintf('%%%s%%', $criteria['searchString']))));
        }

        if (!empty($criteria['type'])) {
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

        if (!empty($criteria['assignee'])) {
            $qb
                ->andWhere('o.responsibility = :assignee')
                ->setParameter('assignee', $criteria['assignee'])
            ;
        }

        if (!empty($criteria['colorStatus'])) {
            $qb
                ->andWhere('o.colorStatus = :colorStatus')
                ->setParameter('colorStatus', $criteria['colorStatus'])
            ;
        }

        if (!empty($criteria['isKeyMilestone'])) {
            $qb->andWhere($qb->expr()->eq('wp.isKeyMilestone', $criteria['isKeyMilestone']));
        }

        if (!empty($criteria['startDate'])) {
            $qb
                ->andWhere('o.scheduledStartAt >= :startDate')
                ->setParameter('startDate', $criteria['startDate'])
            ;
        }

        if (!empty($criteria['endDate'])) {
            $qb
                ->andWhere('o.scheduledFinishAt <= :endDate')
                ->setParameter('endDate', $criteria['endDate'])
            ;
        }

        if (!empty($criteria['dueDate'])) {
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

    /**
     * @param WorkPackage $wp
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return int
     */
    public function getWorkPackageProgress(WorkPackage $wp)
    {
        $columns = [WorkPackage::TYPE_PHASE => 'phase', WorkPackage::TYPE_MILESTONE => 'milestone'];
        $column = $columns[$wp->getType()] ?? null;
        Assert::notNull($column);

        $qb = $this->createQueryBuilder('wp');
        $qb->select('AVG(wp.progress)');
        $qb->andWhere('(wp.parent = :parent OR wp.'.$column.' = :parent) and wp.type = :type AND wp.parent IS NULL');
        $qb->setParameter('parent', $wp->getId());
        $qb->setParameter('type', WorkPackage::TYPE_TASK);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param Project $project
     *
     * @return array
     */
    public function getMilestones(Project $project)
    {
        return $this
            ->createQueryBuilder('o')
            ->where('o.type = :type and o.project = :project')
            ->setParameter('type', WorkPackage::TYPE_MILESTONE)
            ->setParameter('project', $project->getId())
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Project $project
     *
     * @return array
     */
    public function getPhases(Project $project)
    {
        return $this
            ->createQueryBuilder('o')
            ->where('o.type = :type and o.project = :project')
            ->setParameter('type', WorkPackage::TYPE_PHASE)
            ->setParameter('project', $project->getId())
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param WorkPackage       $workPackage
     * @param WorkPackageStatus $status
     *
     * @return int
     */
    public function getStatusCountByPhase(WorkPackage $workPackage, WorkPackageStatus $status)
    {
        return $this
            ->createQueryBuilder('o')
            ->select('COUNT(o)')
            ->where('o.type = :type and o.phase = :phase and o.parent is null and o.workPackageStatus = :status')
            ->setParameter('type', WorkPackage::TYPE_TASK)
            ->setParameter('phase', $workPackage->getId())
            ->setParameter('status', $status->getId())
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param WorkPackage       $workPackage
     * @param WorkPackageStatus $status
     *
     * @return int
     */
    public function getStatusCountByMilestone(WorkPackage $workPackage, WorkPackageStatus $status)
    {
        return $this
            ->createQueryBuilder('o')
            ->select('COUNT(o)')
            ->where('o.type = :type and o.milestone = :milestone and o.parent is null and o.workPackageStatus = :status')
            ->setParameter('type', WorkPackage::TYPE_TASK)
            ->setParameter('milestone', $workPackage->getId())
            ->setParameter('status', $status->getId())
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
