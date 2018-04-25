<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\FileSystem;
use Doctrine\ORM\Event\OnFlushEventArgs;

class FilesystemListener
{
    public function onFlush(OnFlushEventArgs $event)
    {
        $manager = $event->getEntityManager();
        $uof = $manager->getUnitOfWork();
        $fsRepo = $manager->getRepository(FileSystem::class);

        foreach ($uof->getScheduledEntityDeletions() as $entity) {
            if (!($entity instanceof FileSystem)) {
                continue;
            }

            $total = (int) $fsRepo->countTotal();
            if ($entity->getIsDefault() || $total === 1) {
                $manager->persist($entity);
                $manager->refresh($entity);
                $uof->recomputeSingleEntityChangeSet(
                    $manager->getClassMetadata(get_class($entity)),
                    $entity
                );
            }
        }
    }
}
