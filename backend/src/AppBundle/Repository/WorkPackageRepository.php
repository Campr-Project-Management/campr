<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\WorkPackageStatus;
use Doctrine\ORM\Query;

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

        return $qb
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
                $project
            )
        );

        $qb->where(
            $qb->expr()->eq(
                'wp.type',
                WorkPackage::TYPE_TASK
            )
        );

        return $qb->getQuery()->getSingleScalarResult();
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
                'wp.project',
                $milestone->getProject()
            )
        );

        $qb->where(
            $qb->expr()->eq(
                'wp.type',
                WorkPackage::TYPE_TASK
            )
        );

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Return all project workpackages filtered.
     *
     * @param Project           $project
     * @param array             $filters
     * @param WorkPackageStatus $workPackageStatus
     *
     * @return Query
     */
    public function getQueryByProjectAndFilters(Project $project, $filters = [])
    {
        $qb = $this
            ->createQueryBuilder('wp')
            ->innerJoin('wp.project', 'p')
            ->where('p.id = :project')
            ->setParameter('project', $project)
        ;

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
                ->andWhere('wp.actualStartAt >= :startDate')
                ->setParameter('startDate', $filters['startDate'])
            ;
        }

        if (isset($filters['endDate'])) {
            $qb
                ->andWhere('wp.actualFinishAt <= :endDate')
                ->setParameter('endDate', $filters['endDate'])
            ;
        }

        if (isset($filters['dueDate'])) {
            $qb
                ->andWhere('wp.actualFinishAt = :dueDate')
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

        return $qb->getQuery();
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

        return $qb->getQuery()->getSingleScalarResult();
    }
}
