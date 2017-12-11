<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Project;
use AppBundle\Helper\WorkPackageTreeBuilder;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\WorkPackageStatus;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\UnitOfWork;

/**
 * Class WorkPackageListener
 * Reorder work packages after insert or edit one work package.
 */
class WorkPackageListener
{
    /** @var EntityManager $em */
    private $em;

    /** @var \Doctrine\DBAL\Connection $conn */
    private $conn;

    /**
     * @var ArrayCollection|Project[]
     */
    private $projectsToUpdate;

    /**
     * @var ArrayCollection|Project[]
     */
    private $workpackagesToUpdate;

    /**
     * WorkPackageListener constructor.
     */
    public function __construct()
    {
        $this->projectsToUpdate = new ArrayCollection();
        $this->workpackagesToUpdate = new ArrayCollection();
    }

    /**
     * @param OnFlushEventArgs $event
     */
    public function onFlush(OnFlushEventArgs $event)
    {
        $this->em = $event->getEntityManager();
        $this->conn = $this->em->getConnection();
        $uok = $this->em->getUnitOfWork();

        foreach ($uok->getScheduledEntityInsertions() as $entity) {
            if (!($entity instanceof WorkPackage)) {
                continue;
            }

            if ($entity->getProject() && !$this->projectsToUpdate->contains($entity->getProject())) {
                $this->projectsToUpdate->add($entity->getProject());
            }

            $this->workpackagesToUpdate->add($entity);
        }

        foreach ($uok->getScheduledEntityUpdates() as $entity) {
            if (!($entity instanceof WorkPackage)) {
                continue;
            }

            $entityChanges = $uok->getEntityChangeSet($entity);
            if ($entity->getType() == WorkPackage::TYPE_MILESTONE) {
                if (isset($entityChanges['phase'])) {
                    $newPhaseId = $entityChanges['phase'][1]->getId();
                    $this->moveMilestoneTasksToNewPhase($newPhaseId, $entity->getId());
                }
            }

            if ($entity->getType() == WorkPackage::TYPE_TASK && isset($entityChanges['workPackageStatus'])) {
                $this->setStartOrFinishDateToTask($entity, $entityChanges);
            }

            if ($entity->getProject() && !$this->projectsToUpdate->contains($entity->getProject())) {
                $this->projectsToUpdate->add($entity->getProject());
            }

            if (isset($entityChanges['forecastStartAt']) || isset($entityChanges['forecastFinishAt'])) {
                $this->workpackagesToUpdate->add($entity);
            }
        }

        foreach ($uok->getScheduledEntityDeletions() as $entity) {
            if (!($entity instanceof WorkPackage)) {
                continue;
            }

            if ($entity->getProject() && !$this->projectsToUpdate->contains($entity->getProject())) {
                $this->projectsToUpdate->add($entity->getProject());
            }
        }

        foreach ($uok->getScheduledEntityDeletions() as $entity) {
            if (!($entity instanceof Project)) {
                continue;
            }

            if ($this->projectsToUpdate->contains($entity)) {
                $this->projectsToUpdate->removeElement($entity);
            }
        }
    }

    public function postFlush(PostFlushEventArgs $event)
    {
        if (!$this->projectsToUpdate->count()) {
            return;
        }

        $this->em = $event->getEntityManager();
        $uow = $this->em->getUnitOfWork();

        if ($uow->getScheduledEntityInsertions() || $uow->getScheduledEntityUpdates() || $uow->getScheduledEntityDeletions()) {
            return;
        }

        foreach ($this->projectsToUpdate as $project) {
            $sql = sprintf(
                'SELECT id, name, progress, type, puid, phase_id, milestone_id, parent_id
                FROM work_package
                WHERE project_id = %d
                ORDER BY puid ASC',
                $project->getId()
            );
            $workPackages = $this->runSelectQuery($this->em, $sql);
            $tree = WorkPackageTreeBuilder::buildFromRawData($workPackages);

            array_walk($tree, [$this, 'updateWorkPackages']);

            try {
                $this->runUpdateQuery(
                    $this->em,
                    'UPDATE project SET progress = (
                        SELECT AVG(wp.progress)
                        FROM work_package wp
                        WHERE wp.project_id = project.id
                        AND wp.type = :type
                        AND wp.parent_id IS NULL
                    ) WHERE id = :id',
                    [
                        'type' => WorkPackage::TYPE_PHASE,
                        'id' => $project->getId(),
                    ]
                );
            } catch (\Exception $e) {
                // AVG() returns null in MySQL if there's no data and this goes boom
                try {
                    $this->runUpdateQuery(
                        $this->em,
                        'UPDATE project SET progress = (
                            SELECT AVG(wp.progress)
                            FROM work_package wp
                            WHERE wp.project_id = project.id
                            AND wp.type = :type
                            AND wp.parent_id IS NULL
                        ) WHERE id = :id',
                        [
                            'type' => WorkPackage::TYPE_TASK,
                            'id' => $project->getId(),
                        ]
                    );
                } catch (\Exception $e) {
                    $this->runUpdateQuery(
                        $this->em,
                        'UPDATE project SET progress = :progress WHERE id = :id',
                        [
                            'progress' => 100,
                            'id' => $project->getId(),
                        ]
                    );
                }
            }

            if (in_array($this->em->getUnitOfWork()->getEntityState($project), [UnitOfWork::STATE_NEW, UnitOfWork::STATE_MANAGED])) {
                $this->em->refresh($project);
            }
        }

        foreach ($this->workpackagesToUpdate as $workPackage) {
            $this->setUpForecastDatesForMilestone($workPackage->getMilestoneId());
            $this->setUpForecastDatesForPhase($workPackage->getPhaseId());
            // maybe rename this to more sugestive way
            $this->setUpForecastDatesForParent($workPackage);

            foreach ($workPackage->getDependants() as $dependant) {
                $this->setUpForecastDatesForDependant($dependant);
            }
        }
    }

    private function updateWorkPackages(array $item)
    {
        $sql = 'UPDATE work_package SET puid = ?, progress = ? WHERE id = ?';
        $params = [
            $item['puid'],
            $item['progress'],
            $item['id'],
        ];
        $this->em->getConnection()->executeUpdate($sql, $params);

        if (count($item['children'])) {
            array_walk($item['children'], [$this, 'updateWorkPackages']);
        }
    }

    private function runUpdateQuery(EntityManager $em, string $sql, array $params)
    {
        $stmt = $em->getConnection()->prepare($sql);

        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value);
        }

        $stmt->execute();

        return $stmt->rowCount();
    }

    private function runSelectQuery(EntityManager $em, string $sql)
    {
        $stmt = $em->getConnection()->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * When the `phase` of a `milestone` is changed
     * we have to update the `phase` of all tasks that are connected to that milestone.
     *
     * @param int $newPhaseId
     * @param int $milestoneId
     */
    private function moveMilestoneTasksToNewPhase($newPhaseId, $milestoneId)
    {
        $this->runUpdateQuery(
            $this->em,
            'UPDATE work_package 
            SET phase_id  = :phaseId 
            WHERE 
                milestone_id = :milestoneId 
                AND type = ' .WorkPackage::TYPE_TASK,
            [
                'phaseId' => $newPhaseId,
                'milestoneId' => $milestoneId,
            ]
        );
    }

    /**
     * Updates the forecastFinishAt&forecastStartAt for a milestone when a new task is added or changed to that milestone.
     * The new values are the max/min values of forecastFinishAt/forecastStartAt from all the tasks
     * that belong to that milestone.
     *
     * @param $milestoneId int
     */
    private function setUpForecastDatesForMilestone($milestoneId)
    {
        if (!$milestoneId) {
            return;
        }

        $this->runUpdateQuery(
            $this->em,
            'UPDATE work_package 
            SET 
                forecast_start_at  = (SELECT min(forecast_start_at) FROM  (SELECT forecast_start_at FROM work_package WHERE type = ' .WorkPackage::TYPE_TASK.' AND milestone_id = :milestoneId) as temp1),
                forecast_finish_at  = (SELECT max(forecast_finish_at) FROM (SELECT forecast_finish_at FROM work_package WHERE type = ' .WorkPackage::TYPE_TASK.' AND milestone_id = :milestoneId) as temp2)  
            WHERE 
                id = :milestoneId',
            [
                'milestoneId' => $milestoneId,
            ]
        );
    }

    /**
     * Updates the forecastFinishAt&forecastStartAt for a phase when a new task is added or changed to that phase.
     * The new values are the max/min values of forecastFinishAt/forecastStartAt from all the tasks
     * that belong to that phase.
     *
     * @param $phaseId int
     */
    public function setUpForecastDatesForPhase($phaseId)
    {
        if (!$phaseId) {
            return;
        }

        $this->runUpdateQuery(
            $this->em,
            'UPDATE work_package 
            SET 
                forecast_start_at  = (SELECT min(forecast_start_at) FROM  (SELECT forecast_start_at FROM work_package WHERE type = ' .WorkPackage::TYPE_TASK.' AND phase_id = :phaseId) as temp1),
                forecast_finish_at  = (SELECT max(forecast_finish_at) FROM (SELECT forecast_finish_at FROM work_package WHERE type = ' .WorkPackage::TYPE_TASK.' AND phase_id = :phaseId) as temp2) 
            WHERE 
                id = :phaseId',
            [
                'phaseId' => $phaseId,
            ]
        );
    }

    /**
     * Updates the forecastFinishAt&forecastStartAt for the parent when a new task is added to it's children.
     * The new values are the max/min values of forecastFinishAt/forecastStartAt from all the  child tasks.
     *
     * @param $workpackage WorkPackage
     */
    public function setUpForecastDatesForParent($workpackage)
    {
        if (!$workpackage->getParentId()) {
            return;
        }

        $this->runUpdateQuery(
            $this->em,
            'UPDATE work_package 
            SET 
                forecast_start_at  = (SELECT min(forecast_start_at) FROM  (SELECT forecast_start_at FROM work_package WHERE parent_id = :parentId) as temp1),
                forecast_finish_at  = (SELECT max(forecast_finish_at) FROM (SELECT forecast_finish_at FROM work_package WHERE parent_id = :parentId) as temp2) 
            WHERE 
                id = :parentId',
            [
                'parentId' => $workpackage->getParentId(),
            ]
        );

        $this->setUpForecastDatesForParent($workpackage->getParent());
    }

    /**
     * Updates the forecastFinishAt&forecastStartAt for a dependant when a new task is added or changed to that dependant.
     * The new values are the max/min values of forecastFinishAt/forecastStartAt from all the tasks
     * that are dependencies  to that dependant.
     *
     * @param $dependant WorkPackage
     */
    public function setUpForecastDatesForDependant($dependant)
    {
        if (!($dependant instanceof WorkPackage)) {
            return;
        }

        $this->runUpdateQuery(
            $this->em,
            'UPDATE work_package 
            SET 
                forecast_start_at  = (
                    SELECT min(forecast_start_at) 
                    FROM (
                        SELECT forecast_start_at FROM work_package 
                        INNER JOIN  work_package_dependency on work_package.id = work_package_dependency.dependency_id 
                        WHERE type = ' .WorkPackage::TYPE_TASK.' AND dependant_id = :dependantId
                    ) as temp1
                ),
                forecast_finish_at  = (
                    SELECT max(forecast_finish_at) 
                    FROM (
                        SELECT forecast_finish_at FROM work_package 
                        INNER JOIN  work_package_dependency on work_package.id = work_package_dependency.dependency_id 
                        WHERE type = ' .WorkPackage::TYPE_TASK.' AND dependant_id = :dependantId
                    ) as temp2    
                )
            WHERE 
                id = :dependantId',
            [
                'dependantId' => $dependant->getId(),
            ]
        );
        // set up the dependants of the dependant, if they exists
        foreach ($dependant->getDependants() as $supraDependant) {
            $this->setUpForecastDatesForDependant($supraDependant);
        }
    }

    /**
     *  Updates the actualStartAt&actualFinishAt fields of the task
     *  together with the one from phase&milestone when the change of status occurs.
     *
     * @param WorkPackage $task
     * @param array       $entityChanges
     */
    private function setStartOrFinishDateToTask($task, $entityChanges)
    {
        $newWpStatus = $entityChanges['workPackageStatus'][1];

        if ($newWpStatus->getId() == WorkPackageStatus::OPEN) {
            $this->runUpdateQuery(
                $this->em,
                'UPDATE work_package 
            SET actual_start_at  = :actualStartAt 
            WHERE
                actual_start_at IS NULL
                AND id IN (:taskId, :phaseId, :milestoneId)',
                [
                    'actualStartAt' => (new \DateTime())->format('Y-m-d'),
                    'taskId' => $task->getId(),
                    'phaseId' => $task->getPhaseId(),
                    'milestoneId' => $task->getMilestoneId(),
                ]
            );
        }

        if ($newWpStatus->getId() == WorkPackageStatus::COMPLETED) {
            $this->runUpdateQuery(
                $this->em,
                'UPDATE work_package 
                SET actual_finish_at  = :actualFinishAt 
                WHERE
                    actual_finish_at IS NULL
                    AND id = :taskId',
                [
                    'actualFinishAt' => (new \DateTime())->format('Y-m-d'),
                    'taskId' => $task->getId(),
                ]
            );

            $repo = $this
                ->em
                ->getRepository(WorkPackage::class);

            if (1 == $repo->countUncompletedTasksByPhase($task->getPhase())) {
                $this->runUpdateQuery(
                    $this->em,
                    'UPDATE work_package 
                SET actual_finish_at  = :actualFinishAt 
                WHERE
                    actual_finish_at IS NULL
                    AND id = :phaseId',
                    [
                        'actualFinishAt' => (new \DateTime())->format('Y-m-d'),
                        'phaseId' => $task->getPhaseId(),
                    ]
                );
            }

            if (1 == $repo->countUncompletedTasksByMilestone($task->getMilestone())) {
                $this->runUpdateQuery(
                    $this->em,
                    'UPDATE work_package 
                SET actual_finish_at  = :actualFinishAt 
                WHERE
                    actual_finish_at IS NULL
                    AND id = :milestoneId',
                    [
                        'actualFinishAt' => (new \DateTime())->format('Y-m-d'),
                        'milestoneId' => $task->getMilestoneId(),
                    ]
                );
            }
        }
    }
}
