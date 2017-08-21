<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Cost;
use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\WorkPackageStatus;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

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
    public function findUserFiltered(User $user, $filters = [], $select = null)
    {
        $qb = $this
            ->createQueryBuilder('wp')
            ->where('wp.responsibility = :user')
            ->setParameter('user', $user)
        ;

        if ($select) {
            $qb->select($select);
        }

        if (isset($filters['type'])) {
            $qb
                ->andWhere('wp.type = :type')
                ->setParameter('type', $filters['type'])
            ;
        }

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
                ->andWhere('wp.project = :project')
                ->setParameter('project', $filters['project'])
            ;
        }

        if (isset($filters['status'])) {
            $qb
                ->andWhere('wp.colorStatus = :status')
                ->setParameter('status', $filters['status'])
            ;
        }

        if (isset($filters['milestone'])) {
            $qb
                ->andWhere('wp.isKeyMilestone = :milestone')
                ->setParameter('milestone', $filters['milestone'])
            ;
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
            ->findUserFiltered($user, $filters, 'COUNT(DISTINCT wp.id)')
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
     * @param array             $filters
     * @param WorkPackageStatus $workPackageStatus
     *
     * @return array
     */
    public function findByProject(Project $project, $filters = [], WorkPackageStatus $workPackageStatus = null)
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

        if (isset($filters['status'])) {
            $qb
                ->innerJoin('wp.workPackageStatus', 'wps')
                ->andWhere('wps.name = :statusName')
                ->setParameter('statusName', $filters['status'])
            ;
        }

        if (isset($filters['type'])) {
            $qb
                ->andWhere('wp.type = :type')
                ->setParameter('type', $filters['type'])
            ;
        }

        if (isset($filters['milestone'])) {
            $qb
                ->andWhere('wp.isKeyMilestone = :milestone')
                ->setParameter('milestone', $filters['milestone'])
            ;
        }

        return $qb->getQuery();
    }

    /**
     * @param WorkPackage|null $workPackage
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
     * @param Project           $project
     * @param array             $filters
     * @param WorkPackageStatus $workPackageStatus
     *
     * @return Query
     */
    public function getQueryByProjectAndFiltersSortedByStatus(Project $project, $filters = [])
    {
        $qBuilder = $this->getQueryBuilderByProjectAndFilters($project, $filters);

        if (isset($filters['isGrid'])) {
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
     * Return all project workpackages filtered.
     *
     * @param Project $project
     * @param array   $filters
     * @param array   $select
     *
     * @return Query
     */
    public function getQueryByProjectAndFilters(Project $project, $filters = [], $select = null)
    {
        return $this
            ->getQueryBuilderByProjectAndFilters($project, $filters, $select)
            ->getQuery()
        ;
    }

    /**
     * counts the filtered workpackages.
     *
     * @param Project $project
     * @param array   $filters
     *
     * @return int
     */
    public function countTotalByProjectAndFilters(Project $project, $filters = [])
    {
        return (int) $this
            ->getQueryBuilderByProjectAndFilters($project, $filters, 'COUNT(DISTINCT wp.id)')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * Return the query builder for all project workpackages filtered.
     *
     * @param Project $project
     * @param array   $filters
     * @param array   $selects
     *
     * @return QueryBuilder
     */
    public function getQueryBuilderByProjectAndFilters(Project $project, $filters = [], $select = null)
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

        if (isset($filters['phase'])) {
            $qb
                ->andWhere('wp.phase = :phase')
                ->setParameter('phase', $filters['phase'])
            ;
        }

        if (isset($filters['status'])) {
            $qb
                ->andWhere('wp.workPackageStatus = :workPackageStatus')
                ->setParameter('workPackageStatus', $filters['status'])
            ;
        }

        if (isset($filters['searchString'])) {
            $qb
                ->andWhere('wp.name LIKE :searchString')
                ->setParameter('searchString', '%'.$filters['searchString'].'%')
            ;
        }

        if (isset($filters['type'])) {
            $qb
                ->andWhere('wp.type = :type')
                ->setParameter('type', $filters['type'])
            ;
        }

        if (isset($filters['projectUser'])) {
            $qb
                ->andWhere('wp.responsibility = :projectUser')
                ->setParameter('projectUser', $filters['projectUser'])
            ;
        }

        if (isset($filters['colorStatus'])) {
            $qb
                ->andWhere('wp.colorStatus = :colorStatus')
                ->setParameter('colorStatus', $filters['colorStatus'])
            ;
        }

        if (isset($filters['milestone'])) {
            $qb
                ->andWhere('wp.isKeyMilestone = :milestone')
                ->setParameter('milestone', $filters['milestone'])
            ;
        }

        if (isset($filters['startDate'])) {
            $qb
                ->andWhere('wp.scheduledStartAt >= :startDate')
                ->setParameter('startDate', $filters['startDate'])
            ;
        }

        if (isset($filters['endDate'])) {
            $qb
                ->andWhere('wp.scheduledFinishAt <= :endDate')
                ->setParameter('endDate', $filters['endDate'])
            ;
        }

        if (isset($filters['dueDate'])) {
            $qb
                ->andWhere('wp.scheduledFinishAt = :dueDate')
                ->setParameter('dueDate', $filters['dueDate'])
            ;
        }

        if (isset($filters['orderBy']) && isset($filters['order'])) {
            $qb->orderBy('wp.'.$filters['orderBy'], $filters['order']);
        }

        if (isset($filters['pageSize']) && isset($filters['page'])) {
            $qb
                ->setFirstResult($filters['pageSize'] * ($filters['page'] - 1))
                ->setMaxResults($filters['pageSize']);
        }

        return $qb;
    }

    public function getScheduleForProjectSchedule(Project $project)
    {
        $baseStart = $this
            ->getQueryByProjectAndFilters($project, ['orderBy' => 'scheduledStartAt', 'order' => 'ASC'])
            ->setMaxResults(1)
            ->getResult()
        ;
        $baseFinish = $this
            ->getQueryByProjectAndFilters($project, ['orderBy' => 'scheduledFinishAt', 'order' => 'DESC'])
            ->setMaxResults(1)
            ->getResult()
        ;
        $forecastStart = $this
            ->getQueryByProjectAndFilters($project, ['orderBy' => 'forecastStartAt', 'order' => 'ASC'])
            ->setMaxResults(1)
            ->getResult()
        ;
        $forecastFinish = $this
            ->getQueryByProjectAndFilters($project, ['orderBy' => 'forecastFinishAt', 'order' => 'DESC'])
            ->setMaxResults(1)
            ->getResult()
        ;
        $actualStart = $this
            ->getQueryByProjectAndFilters($project, ['orderBy' => 'actualStartAt', 'order' => 'ASC'])
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
    public function countTotalByTypeProjectAndStatus($type, Project $project = null, WorkPackageStatus $status = null)
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

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function getTotalExternalInternalCostsByPhase(Project $project, $type, $userIds = [])
    {
        $qb = $this->getQueryBuilderByProjectAndFilters($project, ['type' => WorkPackage::TYPE_TASK]);

        if (intval($type) === Cost::TYPE_EXTERNAL) {
            $qb->select('SUM(wp.externalActualCost) as actual, SUM(wp.externalForecastCost) as forecast');
        } elseif (intval($type) === Cost::TYPE_INTERNAL) {
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
}
