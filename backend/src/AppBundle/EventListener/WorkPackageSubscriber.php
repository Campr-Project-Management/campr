<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Enum\ProjectModuleTypeEnum;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\Rasci;
use AppBundle\Entity\User;
use AppBundle\Entity\WorkPackage;
use AppBundle\Event\WorkPackageEvent;
use Component\Repository\RepositoryInterface;
use Component\WorkPackage\WorkPackageEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WorkPackageSubscriber implements EventSubscriberInterface
{
    /**
     * @var RepositoryInterface
     */
    private $rasciRepository;

    /**
     * @var RepositoryInterface
     */
    private $projectUserRepository;

    /**
     * WorkPackageSubscriber constructor.
     *
     * @param RepositoryInterface $rasciRepository
     * @param RepositoryInterface $projectUserRepository
     */
    public function __construct(RepositoryInterface $rasciRepository, RepositoryInterface $projectUserRepository)
    {
        $this->rasciRepository = $rasciRepository;
        $this->projectUserRepository = $projectUserRepository;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            WorkPackageEvents::POST_CREATE => 'onPostCreate',
            WorkPackageEvents::POST_UPDATE => 'onPostUpdate',
        ];
    }

    /**
     * @param WorkPackageEvent $event
     */
    public function onPostCreate(WorkPackageEvent $event)
    {
        $wp = $event->getWorkPackage();
        $this->updateResponsibleUserInRasci($wp);
    }

    /**
     * @param WorkPackageEvent $event
     */
    public function onPostUpdate(WorkPackageEvent $event)
    {
        $wp = $event->getWorkPackage();
        $this->updateResponsibleUserInRasci($wp);
    }

    /**
     * @param WorkPackage $wp
     *
     * @return bool
     */
    private function updateResponsibleUserInRasci(WorkPackage $wp): bool
    {
        $project = $wp->getProject();
        if (!$project->hasProjectModule(ProjectModuleTypeEnum::RASCI_MATRIX)) {
            return false;
        }

        $removed = $this->removeAllRasciResponsiblesFromWorkPackage($wp);
        $responsabile = $wp->getResponsibility();
        if (!$responsabile) {
            return $removed;
        }

        $rasci = $this->findRasciByWorkPackageAndUser($wp, $responsabile);
        if (!$rasci) {
            $rasci = new Rasci();
            $rasci->setWorkPackage($wp);
            $rasci->setUser($responsabile);
        }

        $rasci->setData(Rasci::DATA_RESPONSIBLE);
        $this->rasciRepository->add($rasci);

        /** @var ProjectUser $projectUser */
        $projectUser = $responsabile->getProjectUser($wp->getProject());
        $projectUser->setShowInRasci(true);

        $this->projectUserRepository->add($projectUser);

        return true;
    }

    /**
     * @param WorkPackage $wp
     *
     * @return bool
     */
    private function removeAllRasciResponsiblesFromWorkPackage(WorkPackage $wp): bool
    {
        $rascis = $this->rasciRepository->findBy(
            [
                'workPackage' => $wp->getId(),
                'data' => Rasci::DATA_RESPONSIBLE,
            ]
        );

        foreach ($rascis as $rasci) {
            $this->rasciRepository->remove($rasci);
        }

        return count($rascis) > 0;
    }

    /**
     * @param WorkPackage $wp
     * @param User        $user
     *
     * @return Rasci|null
     */
    private function findRasciByWorkPackageAndUser(WorkPackage $wp, User $user)
    {
        /** @var Rasci $rasci */
        $rasci = $this->rasciRepository->findOneBy(
            [
                'workPackage' => $wp->getId(),
                'user' => $user,
            ]
        );

        return $rasci;
    }
}
