<?php

namespace AppBundle\EventListener;

use AppBundle\Command\RedisQueueManagerCommand;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\WorkPackageStatus;
use AppBundle\Event\WorkPackageEvent;
use AppBundle\Repository\WorkPackageStatusRepository;
use AppBundle\Services\WorkPackageRasciSync;
use Component\Repository\RepositoryInterface;
use Component\WorkPackage\WorkPackageEvents;
use Doctrine\ORM\EntityManager;
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
     * @var WorkPackageStatusRepository
     */
    private $workPackageStatusRepository;

    /**
     * @var EntityManager
     */
    private $em;

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
     * @param WorkPackageRasciSync        $workPackageRasciSync
     * @param RepositoryInterface         $workPackageRepository
     * @param WorkPackageStatusRepository $workPackageStatusRepository
     * @param EventDispatcherInterface    $eventDispatcher
     * @param EntityManager               $em
     * @param Client                      $redis
     * @param string                      $env
     */
    public function __construct(
        WorkPackageRasciSync $workPackageRasciSync,
        RepositoryInterface $workPackageRepository,
        WorkPackageStatusRepository $workPackageStatusRepository,
        EventDispatcherInterface $eventDispatcher,
        EntityManager $em,
        Client $redis,
        string $env
    ) {
        $this->workPackageRasciSync = $workPackageRasciSync;
        $this->workPackageRepository = $workPackageRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->workPackageStatusRepository = $workPackageStatusRepository;
        $this->redis = $redis;
        $this->env = $env;
        $this->em = $em;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            WorkPackageEvents::PRE_CREATE => 'onPreCreate',
            WorkPackageEvents::POST_CREATE => 'onPostCreate',
            WorkPackageEvents::POST_UPDATE => 'onPostUpdate',
            WorkPackageEvents::PRE_UPDATE => 'onPreUpdate',
        ];
    }

    /**
     * @param WorkPackageEvent $event
     */
    public function onPreCreate(WorkPackageEvent $event)
    {
        $wp = $event->getWorkPackage();
        if (!$wp->isTask()) {
            return;
        }

        $this->setWorkPackageChildrenSchedule($wp);
    }

    /**
     * @param WorkPackageEvent $event
     */
    public function onPostCreate(WorkPackageEvent $event)
    {
        $wp = $event->getWorkPackage();
        if (!$wp->isTask()) {
            return;
        }

        $this->workPackageRasciSync->sync($wp);
        $this->workPackageRepository->add($wp);
    }

    /**
     * @param WorkPackageEvent $event
     */
    public function onPreUpdate(WorkPackageEvent $event)
    {
        $wp = $event->getWorkPackage();
        if (!$wp->isTask()) {
            return;
        }

        $this->setWorkPackageChildrenSchedule($wp);

        $isProgressChanged = $this->isProgressChanged($wp);
        $isStatusChanged = $this->isStatusChanged($wp);
        if ($isProgressChanged) {
            $this->onProgressChanged($wp);
        } elseif ($isStatusChanged) {
            $this->onStatusChanged($wp);
        }
    }

    /**
     * @param WorkPackageEvent $event
     */
    public function onPostUpdate(WorkPackageEvent $event)
    {
        $wp = $event->getWorkPackage();
        if (!$wp->isTask()) {
            return;
        }

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
     * @param WorkPackage $wp
     *
     * @return bool
     */
    private function isProgressChanged(WorkPackage $wp)
    {
        $uok = $this->em->getUnitOfWork();
        $data = $uok->getOriginalEntityData($wp);
        $progress = $data['progress'] ?? $wp->getProgress();

        return $progress !== $wp->getProgress();
    }

    /**
     * @param WorkPackage $wp
     *
     * @return bool
     */
    private function isStatusChanged(WorkPackage $wp)
    {
        $uok = $this->em->getUnitOfWork();
        $data = $uok->getOriginalEntityData($wp);
        $status = $data['workPackageStatus'] ?? $wp->getWorkPackageStatus();

        return $status !== $wp->getWorkPackageStatus();
    }

    /**
     * @param WorkPackage $wp
     */
    private function onStatusChanged(WorkPackage $wp)
    {
        $status = $wp->getWorkPackageStatus();
        $nextStatus = $this->getNextWorkPackageStatus($status);
        if (!$status) {
            $status = $nextStatus;
            $wp->setWorkPackageStatus($status);
        }

        $min = $status->getProgress();
        $max = $nextStatus ? $nextStatus->getProgress() - 1 : $status->getProgress();
        $range = range($min, $max);
        if (!in_array($wp->getProgress(), $range)) {
            $wp->setProgress($status->getProgress());
        }

        $this->setWorkPackageActualDatesByProgress($wp, $status->getProgress());
    }

    /**
     * @param WorkPackage $wp
     */
    private function onProgressChanged(WorkPackage $wp)
    {
        $statuses = $this->getWorkPackageStatuses();
        $progress = $wp->getProgress();
        foreach ($statuses as $index => $status) {
            $next = $statuses[$index + 1] ?? null;
            if ($progress === $status->getProgress()
                || ($progress > $status->getProgress() && (!$next || $progress < $next->getProgress()))
            ) {
                $wp->setWorkPackageStatus($status);
                break;
            }
        }

        $this->setWorkPackageActualDatesByProgress($wp, $progress);
    }

    /**
     * @param WorkPackage $wp
     * @param int         $progress
     */
    private function setWorkPackageActualDatesByProgress(WorkPackage $wp, int $progress)
    {
        if (0 === $progress) {
            $wp->setActualStartAt(null);
            $wp->setActualFinishAt(null);
        }

        if ($progress > 0) {
            $wp->setActualStartAt($wp->getActualStartAt() ?? new \DateTime());
            $wp->setActualFinishAt(null);
        }

        if (100 === $progress) {
            $wp->setActualStartAt($wp->getActualStartAt() ?? new \DateTime());
            $wp->setActualFinishAt($wp->getActualFinishAt() ?? new \DateTime());
        }
    }

    /**
     * @param WorkPackageStatus $status
     *
     * @return WorkPackageStatus|null
     */
    private function getNextWorkPackageStatus(WorkPackageStatus $status = null)
    {
        $statuses = $this->getWorkPackageStatuses();
        foreach ($statuses as $index => $st) {
            $next = $statuses[$index] ?? null;
            if (!$next) {
                continue;
            }

            if (!$status || $status->getId() === $next->getId()) {
                return $next;
            }
        }

        return null;
    }

    /**
     * @return WorkPackageStatus[]
     */
    private function getWorkPackageStatuses()
    {
        return $this->workPackageStatusRepository->findAllVisibleSortedByProgress();
    }

    /**
     * @param WorkPackage $wp
     */
    private function setWorkPackageChildrenSchedule(WorkPackage $wp)
    {
        $children = $wp->getChildren();

        foreach ($children as $child) {
            if (!$child->getScheduledStartAt() || !$child->getScheduledFinishAt()) {
                $child->setScheduledStartAt($wp->getScheduledStartAt());
                $child->setScheduledFinishAt($wp->getScheduledFinishAt());
            }

            if (!$child->getForecastStartAt() || !$child->getForecastFinishAt()) {
                $child->setForecastStartAt($wp->getForecastStartAt());
                $child->setForecastFinishAt($wp->getForecastFinishAt());
            }
        }
    }
}
