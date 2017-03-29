<?php

namespace AppBundle\EventListener;

use AppBundle\Services\FileSystemService;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use AppBundle\Entity\Media;

/**
 * Class MediaListener
 * Creates or removes a specific media from it's filesystem.
 */
class MediaListener
{
    private $fileSystemService;

    /**
     * MediaListener constructor.
     *
     * @param FileSystemService $fileSystemService
     */
    public function __construct(FileSystemService $fileSystemService)
    {
        $this->fileSystemService = $fileSystemService;
    }

    /**
     * @param LifecycleEventArgs $args
     *
     * @throws \Exception
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof Media) {
            $this->fileSystemService->createFileSystem($entity->getFileSystem());
            $this->fileSystemService->saveMediaFile($entity, $entity->getFile());
        }
    }

    /**
     * @param LifecycleEventArgs $args
     *
     * @throws \Exception
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof Media) {
            $this->fileSystemService->createFileSystem($entity->getFileSystem());
            $this->fileSystemService->removeMediaFile($entity);
        }
    }
}
