<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Enum\ProjectModuleTypeEnum;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\Rasci;
use AppBundle\Entity\User;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\WorkPackageStatus;
use AppBundle\Event\WorkPackageEvent;
use Component\Repository\RepositoryInterface;
use Component\WorkPackage\Calculator\DateRangeCalculatorInterface;
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
     * @var DateRangeCalculatorInterface
     */
    private $phaseActualDatesCalculator;

    /**
     * @var RepositoryInterface
     */
    private $workPackageRepository;

    /**
     * @var RepositoryInterface
     */
    private $workPackageStatusRepository;

    /**
     * WorkPackageSubscriber constructor.
     *
     * @param RepositoryInterface          $rasciRepository
     * @param RepositoryInterface          $projectUserRepository
     * @param RepositoryInterface          $workPackageRepository
     * @param RepositoryInterface          $workPackageStatusRepository
     * @param DateRangeCalculatorInterface $phaseActualDatesCalculator
     */
    public function __construct(
        RepositoryInterface $rasciRepository,
        RepositoryInterface $projectUserRepository,
        RepositoryInterface $workPackageRepository,
        RepositoryInterface $workPackageStatusRepository,
        DateRangeCalculatorInterface $phaseActualDatesCalculator
    ) {
        $this->rasciRepository = $rasciRepository;
        $this->projectUserRepository = $projectUserRepository;
        $this->phaseActualDatesCalculator = $phaseActualDatesCalculator;
        $this->workPackageRepository = $workPackageRepository;
        $this->workPackageStatusRepository = $workPackageStatusRepository;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            WorkPackageEvents::POST_CREATE => 'onPostCreate',
            WorkPackageEvents::POST_UPDATE => 'onPostUpdate',
            WorkPackageEvents::PRE_UPDATE => 'onPreUpdate',
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
        $this->updatePhaseActualDates($wp);
    }

    /**
     * @param WorkPackageEvent $event
     */
    public function onPreUpdate(WorkPackageEvent $event)
    {
        $wp = $event->getWorkPackage();
        $this->recalculateProgress($wp);
        $this->recalculateActualStartAt($wp);
        $this->recalculateActualFinishAt($wp);
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

    /**
     * @param WorkPackage $wp
     */
    private function updatePhaseActualDates(WorkPackage $wp)
    {
        $phase = $wp->getPhase();
        if (!$phase) {
            return;
        }

        list($startAt, $finishAt) = $this->phaseActualDatesCalculator->calculate($phase);

        $phase->setActualStartAt($startAt);
        $phase->setActualFinishAt($finishAt);

        $this->workPackageRepository->add($phase);
    }

    /**
     * @param WorkPackage $wp
     */
    private function recalculateActualStartAt(WorkPackage $wp)
    {
        $status = $wp->getWorkPackageStatus();
        $startAt = $wp->getActualStartAt();
        if ($status->isOnGoing() && !$startAt) {
            $startAt = new \DateTime();
        }

        if ($status->isOpen() || $status->isPending()) {
            $startAt = null;
        }

        $wp->setActualStartAt($startAt);
        if (!$wp->getActualStartAt()) {
            $wp->setActualFinishAt($startAt);
        }
    }

    /**
     * @param WorkPackage $wp
     */
    private function recalculateActualFinishAt(WorkPackage $wp)
    {
        $status = $wp->getWorkPackageStatus();
        $finishAt = $wp->getActualFinishAt();
        if (($status->isCompleted() || $status->isClosed()) && !$finishAt) {
            $finishAt = new \DateTime();
        }

        if ($status->isOpen() || $status->isPending() || $status->isOnGoing()) {
            $finishAt = null;
        }

        $wp->setActualFinishAt($finishAt);
        if (!$wp->getActualStartAt()) {
            $wp->setActualStartAt($finishAt);
        }
    }

    /**
     * @param WorkPackage $wp
     */
    private function recalculateProgress(WorkPackage $wp)
    {
        $status = $wp->getWorkPackageStatus();
        if (!$status || $status->getProgress() < 0) {
            return;
        }

        $closedStatus = $this->getWorkPackageClosedStatus();
        if ($status->isOnGoing()
            && $wp->getProgress() >= $status->getProgress()
            && $wp->getProgress() < $closedStatus->getProgress()
        ) {
            return;
        }

        $wp->setProgress($status->getProgress());
    }

    /**
     * @return WorkPackageStatus
     */
    private function getWorkPackageClosedStatus(): WorkPackageStatus
    {
        /** @var WorkPackageStatus $status */
        foreach ($this->workPackageStatusRepository->findAll() as $status) {
            if ($status->isClosed()) {
                return $status;
            }
        }

        return null;
    }
}
