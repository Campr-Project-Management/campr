<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Contract;
use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectStatus;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\OnFlushEventArgs;

class ContractListener
{
    public function onFlush(OnFlushEventArgs $event): void
    {
        $uow = $event->getEntityManager()->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof Contract) {
                $this->processContract($entity, $event->getEntityManager());
            }
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof Contract) {
                $this->processContract($entity, $event->getEntityManager());
            }
        }
    }

    private function processContract(Contract $contract, EntityManagerInterface $entityManager): void
    {
        $uow = $entityManager->getUnitOfWork();
        if ($contract->isFrozen() && $contract->getApprovedAt() instanceof \DateTime && $contract->getProject()) {
            $project = $contract->getProject();
            $projectStatus = $entityManager->getRepository(ProjectStatus::class)->findOneBy([
                'code' => ProjectStatus::CODE_IN_PROGRESS,
            ]);
            $project->setStatus($projectStatus);
            $uow->persist($project);
            $uow->computeChangeSet(
                $entityManager->getClassMetadata(Project::class),
                $project
            );
        }
    }
}
