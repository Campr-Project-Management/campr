<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\WorkPackageStatus;
use AppBundle\Event\WorkPackageEvent;
use AppBundle\Services\WorkPackageRasciSync;
use Component\Repository\RepositoryInterface;
use Component\WorkPackage\Calculator\DateRangeCalculatorInterface;
use Component\WorkPackage\WorkPackageEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WorkPackageSubscriber implements EventSubscriberInterface
{
    /**
     * @var WorkPackageRasciSync
     */
    private $workPackageRasciSync;

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
     * @param WorkPackageRasciSync         $workPackageRasciSync
     * @param RepositoryInterface          $workPackageRepository
     * @param RepositoryInterface          $workPackageStatusRepository
     * @param DateRangeCalculatorInterface $phaseActualDatesCalculator
     */
    public function __construct(
        WorkPackageRasciSync $workPackageRasciSync,
        RepositoryInterface $workPackageRepository,
        RepositoryInterface $workPackageStatusRepository,
        DateRangeCalculatorInterface $phaseActualDatesCalculator
    ) {
        $this->workPackageRasciSync = $workPackageRasciSync;
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
        $this->workPackageRasciSync->sync($wp);
    }

    /**
     * @param WorkPackageEvent $event
     */
    public function onPostUpdate(WorkPackageEvent $event)
    {
        $wp = $event->getWorkPackage();
        $this->workPackageRasciSync->sync($wp);
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
        if ($status) {
            if ($status->isOnGoing() && !$startAt) {
                $startAt = new \DateTime();
            }

            if ($status->isOpen() || $status->isPending()) {
                $startAt = null;
            }
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
        if ($status) {
            if (($status->isCompleted() || $status->isClosed()) && !$finishAt) {
                $finishAt = new \DateTime();
            }

            if ($status->isOpen() || $status->isPending() || $status->isOnGoing()) {
                $finishAt = null;
            }
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
