<?php

namespace AppBundle\EventListener;

use AppBundle\Event\RasciEvent;
use AppBundle\Services\RasciWorkPackageSync;
use Component\Rasci\RasciEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RasciSubscriber implements EventSubscriberInterface
{
    /**
     * @var RasciWorkPackageSync
     */
    private $rasciWorkPackageSync;

    /**
     * RasciSubscriber constructor.
     *
     * @param RasciWorkPackageSync $rasciWorkPackageSync
     */
    public function __construct(RasciWorkPackageSync $rasciWorkPackageSync)
    {
        $this->rasciWorkPackageSync = $rasciWorkPackageSync;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RasciEvents::POST_CREATE => 'onPostCreate',
            RasciEvents::POST_UPDATE => 'onPostUpdate',
            RasciEvents::POST_REMOVE => 'onPostRemove',
        ];
    }

    /**
     * @param RasciEvent $event
     */
    public function onPostCreate(RasciEvent $event)
    {
        $rasci = $event->getRasci();
        $this->rasciWorkPackageSync->sync($rasci);
    }

    /**
     * @param RasciEvent $event
     */
    public function onPostUpdate(RasciEvent $event)
    {
        $rasci = $event->getRasci();
        $this->rasciWorkPackageSync->sync($rasci);
    }

    /*
     * @param RasciEvent $event
     */
    public function onPostRemove(RasciEvent $event)
    {
        $rasci = $event->getRasci();
        $this->rasciWorkPackageSync->syncRemoveRasci($rasci);
    }
}
