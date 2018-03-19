<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Rasci;
use AppBundle\Event\RasciEvent;
use Component\Rasci\RasciEvents;
use Component\Repository\RepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RasciSubscriber implements EventSubscriberInterface
{
    /**
     * @var RepositoryInterface
     */
    private $workPackageRepository;

    /**
     * RasciSubscriber constructor.
     *
     * @param RepositoryInterface $workPackageRepository
     */
    public function __construct(RepositoryInterface $workPackageRepository)
    {
        $this->workPackageRepository = $workPackageRepository;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RasciEvents::POST_CREATE => 'onPostCreate',
            RasciEvents::POST_UPDATE => 'onPostUpdate',
        ];
    }

    /**
     * @param RasciEvent $event
     */
    public function onPostCreate(RasciEvent $event)
    {
        $rasci = $event->getRasci();
        $this->updateWorkPackageReponsible($rasci);
    }

    /**
     * @param RasciEvent $event
     */
    public function onPostUpdate(RasciEvent $event)
    {
        $rasci = $event->getRasci();
        $this->updateWorkPackageReponsible($rasci);
    }

    /**
     * @param Rasci $rasci
     *
     * @return bool
     */
    private function updateWorkPackageReponsible(Rasci $rasci): bool
    {
        if (Rasci::DATA_RESPONSIBLE !== $rasci->getData()) {
            return false;
        }

        $wp = $rasci->getWorkPackage();
        $wp->setResponsibility($rasci->getUser());

        $this->workPackageRepository->add($wp);

        return true;
    }
}
