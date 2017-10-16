<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Project;
use AppBundle\Helper\WorkPackageTreeBuilder;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use AppBundle\Entity\WorkPackage;
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
     * WorkPackageListener constructor.
     */
    public function __construct()
    {
        $this->projectsToUpdate = new ArrayCollection();
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
        }

        foreach ($uok->getScheduledEntityUpdates() as $entity) {
            if (!($entity instanceof WorkPackage)) {
                continue;
            }

            if ($entity->getProject() && !$this->projectsToUpdate->contains($entity->getProject())) {
                $this->projectsToUpdate->add($entity->getProject());
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
                $this->runUpdateQuery(
                    $this->em,
                    'UPDATE project SET progress = :progress WHERE id = :id',
                    [
                        'progress' => 100,
                        'id' => $project->getId(),
                    ]
                );
            }

            if (in_array($this->em->getUnitOfWork()->getEntityState($project), [UnitOfWork::STATE_NEW, UnitOfWork::STATE_MANAGED])) {
                $this->em->refresh($project);
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
}
