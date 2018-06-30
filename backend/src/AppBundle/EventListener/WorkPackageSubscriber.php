<?php

namespace AppBundle\EventListener;

use AppBundle\Command\RedisQueueManagerCommand;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\WorkPackageStatus;
use AppBundle\Event\WorkPackageEvent;
use AppBundle\Services\WorkPackageRasciSync;
use Component\Repository\RepositoryInterface;
use Component\WorkPackage\WorkPackageEvents;
use Predis\Client;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WorkPackageSubscriber implements EventSubscriberInterface
{
    /**
     * @var WorkPackageRasciSync
     */
    private $workPackageRasciSync;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var RepositoryInterface
     */
    private $workPackageRepository;

    /**
     * @var RepositoryInterface
     */
    private $workPackageStatusRepository;

    /**
     * @var Client
     */
    private $redis;

    /**
     * @var string
     */
    private $env;

    /**
     * WorkPackageSubscriber constructor.
     *
     * @param WorkPackageRasciSync     $workPackageRasciSync
     * @param RepositoryInterface      $workPackageRepository
     * @param RepositoryInterface      $workPackageStatusRepository
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        WorkPackageRasciSync $workPackageRasciSync,
        RepositoryInterface $workPackageRepository,
        RepositoryInterface $workPackageStatusRepository,
        EventDispatcherInterface $eventDispatcher,
        Client $redis,
        string $env
    ) {
        $this->workPackageRasciSync = $workPackageRasciSync;
        $this->workPackageRepository = $workPackageRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->workPackageStatusRepository = $workPackageStatusRepository;
        $this->redis = $redis;
        $this->env = $env;
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

        $this->workPackageRepository->add($wp);
    }

    /**
     * @param WorkPackageEvent $event
     */
    public function onPostUpdate(WorkPackageEvent $event)
    {
        $wp = $event->getWorkPackage();
        $this->workPackageRasciSync->sync($wp);

        $this->workPackageRepository->add($wp);

        if ($wp->getProject()) {
            $this->redis->rpush(RedisQueueManagerCommand::DEFAULT, [
                sprintf(
                    '--env=%s app:update:puids %s',
                    $this->env,
                    $wp->getProjectId()
                ),
            ]);
        }
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
        } elseif ($wp->getActualStartAt() && $finishAt && $wp->getActualStartAt()->getTimestamp() > $finishAt->getTimestamp()) {
            $wp->setActualStartAt(clone $finishAt);
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
     * @return WorkPackageStatus|null
     */
    private function getWorkPackageClosedStatus()
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
