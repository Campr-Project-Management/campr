<?php

namespace AppBundle\EventListener;

use AppBundle\Services\FileSystemService;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use AppBundle\Entity\Media;

/**
 * Class MediaSubscriber
 * Creates or removes a specific media from it's filesystem.
 */
class MediaSubscriber implements EventSubscriber
{
    /**
     * @var FileSystemService
     */
    private $fileSystemService;

    /**
     * MediaSubscriber constructor.
     *
     * @param FileSystemService $fileSystemService
     */
    public function __construct(FileSystemService $fileSystemService)
    {
        $this->fileSystemService = $fileSystemService;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'postRemove',
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     *
     * @throws \Exception
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!($entity instanceof Media)) {
            return;
        }

        $this->fileSystemService->createFileSystem($entity->getFileSystem());
        $this->fileSystemService->saveMediaFile($entity, $entity->getFile());
    }

    /**
     * @param LifecycleEventArgs $args
     *
     * @throws \Exception
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!($entity instanceof Media)) {
            return;
        }

        $this->fileSystemService->createFileSystem($entity->getFileSystem());
        $this->fileSystemService->removeMediaFile($entity);
    }
}
