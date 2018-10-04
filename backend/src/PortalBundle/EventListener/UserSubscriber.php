<?php

namespace PortalBundle\EventListener;

use AppBundle\Entity\User;
use Component\User\UserEvents;
use PortalBundle\Client\Http\UserClient;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserClient
     */
    private $client;

    /**
     * UserSubscriber constructor.
     *
     * @param UserClient $client
     */
    public function __construct(UserClient $client)
    {
        $this->client = $client;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UserEvents::SYNC => 'onSync',
        ];
    }

    /**
     * @param GenericEvent $event
     *
     * @return User
     */
    public function onSync(GenericEvent $event)
    {
        $user = $event->getSubject();
        if (!($user instanceof User)) {
            return null;
        }

         $this->client->get($user);
    }
}
