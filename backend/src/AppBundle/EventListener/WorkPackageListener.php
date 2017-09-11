<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\WorkPackageStatus;
use AppBundle\Repository\WorkPackageRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use AppBundle\Entity\WorkPackage;

/**
 * Class WorkPackageListener
 * Reorder work packages after insert or edit one work package.
 */
class WorkPackageListener
{
    /** @var EntityManager $em */
    private $em;

    /** @var WorkPackageRepository $repository */
    private $repository;

    /** @var \Doctrine\DBAL\Connection $conn */
    private $conn;

    /**
     * @param OnFlushEventArgs $event
     */
    public function onFlush(OnFlushEventArgs $event)
    {
        $this->em = $event->getEntityManager();
        $this->conn = $this->em->getConnection();
        $uok = $this->em->getUnitOfWork();
        $updateFromInsert = false;

        foreach ($uok->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof WorkPackage) {
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
            }
        }
        // Disabled due to a bug. WorkPackage constructor PUID set.
        return;
        $this->em = $event->getEntityManager();
        $this->repository = $this->em->getRepository(WorkPackage::class);
        $this->conn = $this->em->getConnection();
        $uok = $this->em->getUnitOfWork();
        $updateFromInsert = false;

        foreach ($uok->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof WorkPackage) {
                if (!$entity->getPuid()) {
                    $this->insertWorkPackage($entity);
                } else {
                    $workPackage = $this->repository
                        ->findOneBy([
                            'puid' => $entity->getPuid(),
                            'project' => $entity->getProject(),
                        ])
                    ;

                    if ($workPackage) {
                        $updateFromInsert = true;
                        /** @var WorkPackage $workPackage */
                        if ($workPackage->getParent()) {
                            $workPackage = $this->repository->findLastInsertedByParent($entity->getProject(), $workPackage->getParent());
                        } else {
                            $workPackage = $this->repository->findLastInsertedByParent($entity->getProject());
                        }

                        $this->shiftWorkPackagesToRight($workPackage, $entity->getPuid());
                    } else {
                        $this->insertWorkPackage($entity);
                    }
                }

                $uok->recomputeSingleEntityChangeSet(
                    $this->em->getClassMetadata(WorkPackage::class),
                    $entity
                );
            }
        }

        foreach ($uok->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof WorkPackage) {
                if (!$updateFromInsert) {
                    $entityChanges = $uok->getEntityChangeSet($entity);
                    if (array_key_exists('parent', $entityChanges)) {
                        $this->updatePuidOnParentChange($entity);
                    } elseif (array_key_exists('puid', $entityChanges)) {
                        $oldPuid = $entityChanges['puid'][0];
                        $newPuid = $entityChanges['puid'][1];

                        $oldWorkPackage = $this
                            ->repository
                            ->findOneBy([
                                'puid' => $oldPuid,
                                'project' => $entity->getProject(),
                            ])
                        ;
                        $newWorkPackage = $this
                            ->repository
                            ->findOneBy([
                                'puid' => $newPuid,
                                'project' => $entity->getProject(),
                            ])
                        ;

                        if (!$newWorkPackage) {
                            $this->updateWithNonExistentPuid($entity, $oldPuid, $newPuid);
                        } elseif ($oldWorkPackage->getParent() == $newWorkPackage->getParent()) {
                            $this->updatePuidOnTheSameParent($entity, $oldPuid, $newPuid);
                        } else {
                            $this->updateToExistentPuid($entity, $oldPuid, $newPuid);
                        }
                    }

                    $uok->recomputeSingleEntityChangeSet(
                        $this->em->getClassMetadata(WorkPackage::class),
                        $entity
                    );
                }
            }
        }

        foreach ($uok->getScheduledEntityDeletions() as $entity) {
            if ($entity instanceof WorkPackage) {
                /** @var WorkPackage $entity */
                $puid = $entity->getPuid();

                if ($entity->getParent()) {
                    $siblingNumber = (int) (substr($puid, strrpos($puid, '.') + 1)) + 1;
                    $puid = sprintf('%s.%d', $entity->getParent()->getPuid(), $siblingNumber);

                    $workPackage = $this
                        ->repository
                        ->findOneBy([
                            'puid' => $puid,
                            'project' => $entity->getProject(),
                        ])
                    ;
                    $lastWorkPackage = $this
                        ->repository
                        ->findLastInsertedByParent($entity->getProject(), $entity->getParent())
                    ;
                } else {
                    $puid = (int) $puid + 1;

                    $workPackage = $this
                        ->repository
                        ->findOneBy([
                            'puid' => $puid,
                            'project' => $entity->getProject(),
                        ])
                    ;
                    $lastWorkPackage = $this
                        ->repository
                        ->findLastInsertedByParent($entity->getProject())
                    ;
                }

                $workPackageId = $entity->getId();
                $stmt = $this->conn->prepare('DELETE FROM work_package WHERE id = :id');
                $stmt->bindParam(':id', $workPackageId);
                $stmt->execute();

                if ($workPackage) {
                    $this->shiftWorkPackagesToLeft($workPackage, $lastWorkPackage->getPuid());
                }
            }
        }
    }

    private function insertWorkPackage(WorkPackage $workPackage)
    {
        if (!$workPackage->getParent()) {
            $lastWorkPackage = $this
                ->repository
                ->findLastInsertedByParent($workPackage->getProject())
            ;

            $puid = 1;
            if ($lastWorkPackage) {
                $puid += $lastWorkPackage->getPuid();
            }
            $workPackage->setPuid($puid);
        } else {
            $parentPuid = $workPackage->getParent()->getPuid();
            $lastWorkPackage = $this->repository
                ->findLastInsertedByParent(
                    $workPackage->getProject(),
                    $workPackage->getParent()
                );

            if ($lastWorkPackage) {
                $lastSiblingPuid = $lastWorkPackage->getPuid();
                $numOfSiblings = substr($lastSiblingPuid, strrpos($lastSiblingPuid, '.') + 1);
            } else {
                $numOfSiblings = 0;
            }
            $workPackage->setPuid(sprintf('%s.%d', $parentPuid, $numOfSiblings + 1));
        }
    }

    private function updatePuidOnParentChange(WorkPackage $workPackage)
    {
        if ($workPackage->getParent()) {
            $lastWorkPackage = $this
                ->repository
                ->findLastInsertedByParent($workPackage->getProject(), $workPackage->getParent())
            ;
            $newPuid = sprintf('%s.%d', $workPackage->getParent()->getPuid(), 1);

            if ($lastWorkPackage) {
                $puid = $lastWorkPackage->getPuid();
                $siblingNumber = (int) substr($puid, strrpos($puid, '.') + 1);
                $newPuid = sprintf('%s.%d', $workPackage->getParent()->getPuid(), $siblingNumber + 1);
            }
        } else {
            $newPuid = 1;
            $lastWorkPackage = $this
                ->repository
                ->findLastInsertedByParent($workPackage->getProject())
            ;

            if ($lastWorkPackage) {
                $newPuid = (int) $lastWorkPackage->getPuid();
                $newPuid += 1;
            }
        }

        $workPackage->setPuid($newPuid);
        $this->reorderWorkPackages($workPackage, $newPuid);
    }

    private function updateWithNonExistentPuid(WorkPackage $entity, $oldPuid, $newPuid)
    {
        $entity->setParent();

        if (strrpos($newPuid, '.')) {
            $parentPuid = substr($newPuid, 0, strrpos($newPuid, '.'));

            $workPackage = $this
                ->repository
                ->findOneBy([
                    'puid' => $parentPuid,
                    'project' => $entity->getProject(),
                ])
            ;

            if ($workPackage) {
                $entity->setParent($workPackage);
                $lastWorkPackage = $this
                    ->repository
                    ->findLastInsertedByParent($entity->getProject(), $workPackage)
                ;
                $newPuid = sprintf('%s.%d', $workPackage->getPuid(), 1);

                if ($lastWorkPackage) {
                    if ($lastWorkPackage->getId() == $entity->getId()) {
                        $newPuid = $oldPuid;
                    } else {
                        $puid = $lastWorkPackage->getPuid();
                        $siblingNumber = (int) substr($puid, strrpos($puid, '.') + 1);
                        $newPuid = sprintf('%s.%d', $entity->getParent()->getPuid(), $siblingNumber + 1);
                    }
                }
            } else {
                $newPuid = 1;
                $lastWorkPackage = $this
                    ->repository
                    ->findLastInsertedByParent($entity->getProject())
                ;

                if ($lastWorkPackage) {
                    if ($lastWorkPackage->getId() == $entity->getId()) {
                        $newPuid = $oldPuid;
                    } else {
                        $newPuid = (int) $lastWorkPackage->getPuid();
                        $newPuid += 1;
                    }
                }
            }
        } else {
            $newPuid = 1;
            $lastWorkPackage = $this
                ->repository
                ->findLastInsertedByParent($entity->getProject())
            ;

            if ($lastWorkPackage) {
                if ($lastWorkPackage->getId() == $entity->getId()) {
                    $newPuid = $oldPuid;
                } else {
                    $newPuid = (int) $lastWorkPackage->getPuid();
                    $newPuid += 1;
                }
            }
        }

        //Shift work packages between old puid and last puid with one position to left
        if ($entity->getParent()) {
            $oldParentPuid = substr($oldPuid, 0, strrpos($oldPuid, '.'));
            $siblingNumber = (int) (substr($oldPuid, strrpos($oldPuid, '.') + 1)) + 1;
            $puid = sprintf('%s.%d', $oldParentPuid, $siblingNumber);

            $workPackage = $this
                ->repository
                ->findOneBy([
                    'puid' => $puid,
                    'project' => $entity->getProject(),
                ])
            ;
        } else {
            $workPackage = $this
                ->repository
                ->findOneBy([
                    'puid' => $oldPuid + 1,
                    'project' => $entity->getProject(),
                ])
            ;
        }

        if ($workPackage) {
            if ($workPackage->getParent()) {
                $lastWorkPackage = $this
                    ->repository
                    ->findLastInsertedByParent($entity->getProject(), $workPackage->getParent())
                ;
            } else {
                $lastWorkPackage = $this
                    ->repository
                    ->findLastInsertedByParent($entity->getProject())
                ;
            }

            $workPackageId = $entity->getId();
            $stmt = $this->conn->prepare('UPDATE work_package SET puid  = :puid WHERE id = :id');
            $stmt->bindParam(':id', $workPackageId);
            $stmt->bindParam(':puid', $newPuid);
            $stmt->execute();

            $lastWorkPackagePuid = $lastWorkPackage->getPuid();
            $this->shiftWorkPackagesToLeft($workPackage, $lastWorkPackagePuid);

            if (strrpos($lastWorkPackagePuid, '.')) {
                $lastWorkPackagePuidParent = substr($lastWorkPackagePuid, 0, strrpos($lastWorkPackagePuid, '.'));
                $newPuidParent = substr($newPuid, 0, strrpos($newPuid, '.'));

                if ($newPuidParent == $lastWorkPackagePuidParent) {
                    $siblingNumber = (int) (substr($newPuid, strrpos($newPuid, '.') + 1)) - 1;
                    $newPuid = sprintf('%s.%d', $newPuidParent, $siblingNumber);
                    $this->shiftWorkPackagesToLeft($entity, $entity->getPuid());
                }
            } elseif (!strrpos($lastWorkPackagePuid, '.') && !strrpos($newPuid, '.')) {
                $newPuid -= 1;
                $this->shiftWorkPackagesToLeft($entity, $entity->getPuid());
            }
        }

        $entity->setPuid($newPuid);
        $this->reorderWorkPackages($entity, $newPuid);
    }

    private function updatePuidOnTheSameParent(WorkPackage $entity, $oldPuid, $newPuid)
    {
        //Release old puid in order to shift all work packages
        $freePuidNumber = $this
            ->repository
            ->findMaxPuid($entity->getProject())
        ;
        $freePuidNumber += 1;
        $workPackageId = $entity->getId();

        $stmt = $this->conn->prepare('UPDATE work_package SET puid  = :puid WHERE id = :id');
        $stmt->bindParam(':id', $workPackageId);
        $stmt->bindParam(':puid', $freePuidNumber);
        $stmt->execute();

        if ($newPuid < $oldPuid) {
            //Shift work packages between old puid and new puid with one position to right
            if ($entity->getParent()) {
                $siblingNumber = (int) (substr($oldPuid, strrpos($oldPuid, '.') + 1)) - 1;
                $puid = sprintf('%s.%d', $entity->getParent()->getPuid(), $siblingNumber);

                $workPackage = $this
                    ->repository
                    ->findOneBy([
                        'puid' => $puid,
                        'project' => $entity->getProject(),
                    ])
                ;
            } else {
                $workPackage = $this
                    ->repository
                    ->findOneBy([
                        'puid' => $oldPuid - 1,
                        'project' => $entity->getProject(),
                    ])
                ;
            }

            $this->shiftWorkPackagesToRight($workPackage, $newPuid);
            $this->reorderWorkPackages($entity, $newPuid);
        } else {
            //Shift work packages between old puid and new puid with one position to left
            if ($entity->getParent()) {
                $siblingNumber = (int) (substr($oldPuid, strrpos($oldPuid, '.') + 1)) + 1;
                $puid = sprintf('%s.%d', $entity->getParent()->getPuid(), $siblingNumber);

                $workPackage = $this
                    ->repository
                    ->findOneBy([
                        'puid' => $puid,
                        'project' => $entity->getProject(),
                    ])
                ;
            } else {
                $workPackage = $this
                    ->repository
                    ->findOneBy([
                        'puid' => $oldPuid + 1,
                        'project' => $entity->getProject(),
                    ])
                ;
            }

            $this->shiftWorkPackagesToLeft($workPackage, $newPuid);
            $this->reorderWorkPackages($entity, $newPuid);
        }
    }

    private function updateToExistentPuid(WorkPackage $entity, $oldPuid, $newPuid)
    {
        $newWorkPackage = $this
            ->repository
            ->findOneBy([
                'puid' => $newPuid,
                'project' => $entity->getProject(),
            ])
        ;

        if ($entity->getParent()) {
            $siblingNumber = (int) (substr($oldPuid, strrpos($oldPuid, '.') + 1)) + 1;
            $puid = sprintf('%s.%d', $entity->getParent()->getPuid(), $siblingNumber);

            $workPackage = $this
                ->repository
                ->findOneBy([
                    'puid' => $puid,
                    'project' => $entity->getProject(),
                ])
            ;
        } else {
            $workPackage = $this
                ->repository
                ->findOneBy([
                    'puid' => $oldPuid + 1,
                    'project' => $entity->getProject(),
                ])
            ;
        }

        if ($workPackage) {
            $lastWorkPackage = $this
                ->repository
                ->findLastInsertedByParent($entity->getProject(), $workPackage->getParent())
            ;

            //Release old puid in order to shift all work packages
            $freePuidNumber = $this
                ->repository
                ->findMaxPuid($entity->getProject())
            ;
            $freePuidNumber += 1;
            $workPackageId = $entity->getId();

            $stmt = $this->conn->prepare('UPDATE work_package SET puid  = :puid WHERE id = :id');
            $stmt->bindParam(':id', $workPackageId);
            $stmt->bindParam(':puid', $freePuidNumber);
            $stmt->execute();

            //Shift old work package siblings with one position to left
            $this->shiftWorkPackagesToLeft($workPackage, $lastWorkPackage->getPuid());
        }

        //Shift new work package siblings one position to the right
        $workPackage = $this
            ->repository
            ->findOneBy([
                'puid' => $newPuid,
                'project' => $entity->getProject(),
            ])
        ;

        if ($workPackage) {
            $lastWorkPackage = $this
                ->repository
                ->findLastInsertedByParent($entity->getProject(), $workPackage->getParent())
            ;
            $this->shiftWorkPackagesToRight($lastWorkPackage, $newPuid);
        }

        //Update work package parent
        if ($newWorkPackage && $newWorkPackage->getParent()) {
            $entity->setParent($newWorkPackage->getParent());
        } else {
            $entity->setParent();
        }
    }

    private function shiftWorkPackagesToRight(WorkPackage $workPackage, $entityPuid)
    {
        while ($workPackage && $workPackage->getPuid() != $entityPuid) {
            if ($workPackage->getParent()) {
                $puid = $workPackage->getPuid();
                $siblingNumber = (int) (substr($puid, strrpos($puid, '.') + 1)) + 1;
                $puid = sprintf('%s.%d', $workPackage->getParent()->getPuid(), $siblingNumber);
            } else {
                $puid = (int) $workPackage->getPuid();
                $puid += 1;
            }
            $workPackageId = $workPackage->getId();

            $stmt = $this->conn->prepare('UPDATE work_package SET puid  = :puid WHERE id = :id');
            $stmt->bindParam(':id', $workPackageId);
            $stmt->bindParam(':puid', $puid);
            $stmt->execute();

            $this->reorderWorkPackages($workPackage, (string) $puid);

            if ($workPackage->getParent()) {
                $puid = sprintf('%s.%d', $workPackage->getParent()->getPuid(), $siblingNumber - 2);
            } else {
                $puid -= 2;
            }

            $workPackage = $this->repository
                ->findOneBy([
                    'puid' => (string) $puid,
                    'project' => $workPackage->getProject(),
                ])
            ;
        }

        if ($workPackage->getParent()) {
            $puid = $workPackage->getPuid();
            $siblingNumber = (int) (substr($puid, strrpos($puid, '.') + 1)) + 1;
            $puid = sprintf('%s.%d', $workPackage->getParent()->getPuid(), $siblingNumber);
        } else {
            $puid = (int) $workPackage->getPuid();
            $puid += 1;
        }
        $workPackageId = $workPackage->getId();

        $stmt = $this->conn->prepare('UPDATE work_package SET puid  = :puid WHERE id = :id');
        $stmt->bindParam(':id', $workPackageId);
        $stmt->bindParam(':puid', $puid);
        $stmt->execute();

        $this->reorderWorkPackages($workPackage, (string) $puid);
    }

    private function shiftWorkPackagesToLeft(WorkPackage $workPackage, $entityPuid)
    {
        while ($workPackage && $workPackage->getPuid() != $entityPuid) {
            if ($workPackage->getParent()) {
                $puid = $workPackage->getPuid();
                $siblingNumber = (int) (substr($puid, strrpos($puid, '.') + 1)) - 1;
                $puid = sprintf('%s.%d', $workPackage->getParent()->getPuid(), $siblingNumber);
            } else {
                $puid = (int) $workPackage->getPuid();
                $puid -= 1;
            }
            $workPackageId = $workPackage->getId();

            $stmt = $this->conn->prepare('UPDATE work_package SET puid  = :puid WHERE id = :id');
            $stmt->bindParam(':id', $workPackageId);
            $stmt->bindParam(':puid', $puid);
            $stmt->execute();

            $this->reorderWorkPackages($workPackage, (string) $puid);

            if ($workPackage->getParent()) {
                $puid = sprintf('%s.%d', $workPackage->getParent()->getPuid(), $siblingNumber + 2);
            } else {
                $puid += 2;
            }

            $workPackage = $this->repository
                ->findOneBy([
                    'puid' => (string) $puid,
                    'project' => $workPackage->getProject(),
                ])
            ;
        }

        if ($workPackage->getParent()) {
            $puid = $workPackage->getPuid();
            $siblingNumber = (int) (substr($puid, strrpos($puid, '.') + 1)) - 1;
            $puid = sprintf('%s.%d', $workPackage->getParent()->getPuid(), $siblingNumber);
        } else {
            $puid = (int) $workPackage->getPuid();
            $puid -= 1;
        }
        $workPackageId = $workPackage->getId();

        $stmt = $this->conn->prepare('UPDATE work_package SET puid  = :puid WHERE id = :id');
        $stmt->bindParam(':id', $workPackageId);
        $stmt->bindParam(':puid', $puid);
        $stmt->execute();

        $this->reorderWorkPackages($workPackage, (string) $puid);
    }

    private function reorderWorkPackages(WorkPackage $workPackage, $newParentWorkPackagePuid)
    {
        $uok = $this->em->getUnitOfWork();

        if ($workPackage->getChildren()->count() > 0) {
            foreach ($workPackage->getChildren() as $child) {
                $childPuid = $child->getPuid();
                $siblingNumber = substr($childPuid, strrpos($childPuid, '.') + 1);
                $child->setPuid(sprintf('%s.%d', $newParentWorkPackagePuid, $siblingNumber));

                $uok->recomputeSingleEntityChangeSet(
                    $this->em->getClassMetadata(WorkPackage::class),
                    $child
                );

                $this->reorderWorkPackages($child, $child->getPuid());
            }
        }
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
        $query = $this->conn->prepare(
            'UPDATE work_package 
            SET phase_id  = :phaseId 
            WHERE 
                milestone_id = :milestoneId 
                AND type = ' .WorkPackage::TYPE_TASK
        );
        $query->bindParam(':phaseId', $newPhaseId);
        $query->bindParam(':milestoneId', $milestoneId);
        $query->execute();
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
            if ($task->getActualStartAt() === null) {
                $task->setActualStartAt(new \DateTime());
                $this->em->persist($task);
            }
            if ($task->getPhase() instanceof WorkPackage && $task->getPhase()->getActualStartAt() === null) {
                $phase = $task->getPhase();
                $phase->setActualStartAt(new \DateTime());
                $this->em->persist($phase);
                $this->em->flush();
            }
            if ($task->getMilestone() instanceof WorkPackage && $task->getMilestone()->getActualStartAt() === null) {
                $milestone = $task->getMilestone();
                $milestone->setActualStartAt(new \DateTime());
                $this->em->persist($milestone);
                $this->em->flush();
            }
        }

        if ($newWpStatus->getId() == WorkPackageStatus::COMPLETED) {
            if ($task->getActualFinishAt() === null) {
                $task->setActualFinishAt(new \DateTime());
                $this->em->persist($task);
            }
        }

        if ($task->getPhase() instanceof WorkPackage) {
            $phaseHasAllTasksCompleted = true;
            $tasksAttachedToPhase = $this->em
                ->getRepository(WorkPackage::class)
                ->findBy([
                    'type' => WorkPackage::TYPE_TASK,
                    'phase' => $task->getPhase(),
                ])
            ;
            foreach ($tasksAttachedToPhase as $task) {
                if ($task->getWorkPackageStatusId() !== WorkPackageStatus::COMPLETED) {
                    $phaseHasAllTasksCompleted = 0;
                    break;
                }
            }
            if ($phaseHasAllTasksCompleted) {
                $phase = $task->getPhase();
                $phase->setActualFinishAt(new \DateTime());
                $this->em->persist($phase);
                $this->em->flush();
            }
        }

        if ($task->getMilestone() instanceof WorkPackage) {
            $milestoneHasAllTasksCompleted = true;
            $tasksAttachedToMilestone = $this->em
                ->getRepository(WorkPackage::class)
                ->findBy([
                    'type' => WorkPackage::TYPE_TASK,
                    'milestone' => $task->getMilestone(),
                ])
            ;
            foreach ($tasksAttachedToMilestone as $task) {
                if ($task->getWorkPackageStatusId() !== WorkPackageStatus::COMPLETED) {
                    $milestoneHasAllTasksCompleted = 0;
                    break;
                }
            }
            if ($milestoneHasAllTasksCompleted) {
                $milestone = $task->getMilestone();
                $milestone->setActualFinishAt(new \DateTime());
                $this->em->persist($milestone);
                $this->em->flush();
            }
        }
    }
}
