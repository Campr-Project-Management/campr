<?php

namespace AppBundle\EventListener;

use AppBundle\Services\FileSystemService;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use AppBundle\Entity\Media;

class MediaListener
{
    private $fileSystemService;

    public function __construct(FileSystemService $fileSystemService)
    {
        $this->fileSystemService = $fileSystemService;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof Media) {
            $this->fileSystemService->createFileSystem($entity->getFileSystem());
            $this->fileSystemService->saveMediaFile($entity, $entity->getFile());
        }
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof Media) {
            $this->fileSystemService->createFileSystem($entity->getFileSystem());
            $this->fileSystemService->removeMediaFile($entity);
        }
    }
}
