<?php

namespace AppBundle\EventListener;

use AppBundle\Event\ProjectModuleEvent;
use AppBundle\Services\WorkPackageRasciSync;
use Component\Repository\RepositoryInterface;
use Component\ProjectModule\ProjectModuleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProjectModuleSubscriber implements EventSubscriberInterface
{
    /**
     * @var WorkPackageRasciSync
     */
    private $workPackageRasciSync;

    /**
     * @var RepositoryInterface
     */
    private $workPackageRepository;

    /**
     * ProjectModuleSubscriber constructor.
     *
     * @param WorkPackageRasciSync $workPackageRasciSync
     * @param RepositoryInterface  $workPackageRepository
     */
    public function __construct(
        WorkPackageRasciSync $workPackageRasciSync,
        RepositoryInterface $workPackageRepository
    ) {
        $this->workPackageRasciSync = $workPackageRasciSync;
        $this->workPackageRepository = $workPackageRepository;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ProjectModuleEvents::POST_UPDATE => 'onPostUpdate',
        ];
    }

    /**
     * @param ProjectModuleEvent $event
     */
    public function onPostUpdate(ProjectModuleEvent $event)
    {
        $project = $event->getProjectModule()->getProject();
        $workPackages = $project->getWorkPackages();
        if (count($workPackages)) {
            foreach ($workPackages as $wp) {
                $this->workPackageRasciSync->sync($wp);
                $this->workPackageRepository->add($wp);
            }
        }
    }
}
