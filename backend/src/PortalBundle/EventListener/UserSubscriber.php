<?php

namespace PortalBundle\EventListener;

use AppBundle\Entity\User;
use Component\Team\Context\TeamContextInterface;
use Component\User\Context\UserContextInterface;
use Component\User\UserEvents;
use GuzzleHttp\Exception\BadResponseException;
use PortalBundle\Client\Http\UserClient;
use PortalBundle\Client\Http\WorkspaceMemberClient;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserClient
     */
    private $client;

    /**
     * @var WorkspaceMemberClient
     */
    private $workspaceMemberClient;

    /**
     * @var UserContextInterface
     */
    private $userContext;

    /**
     * @var TeamContextInterface
     */
    private $teamContext;

    /**
     * UserSubscriber constructor.
     *
     * @param UserClient            $client
     * @param WorkspaceMemberClient $workspaceMemberClient
     * @param UserContextInterface  $userContext
     * @param TeamContextInterface  $teamContext
     */
    public function __construct(
        UserClient $client,
        WorkspaceMemberClient $workspaceMemberClient,
        UserContextInterface $userContext,
        TeamContextInterface $teamContext
    ) {
        $this->client = $client;
        $this->workspaceMemberClient = $workspaceMemberClient;
        $this->userContext = $userContext;
        $this->teamContext = $teamContext;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UserEvents::SYNC => 'onSync',
            UserEvents::PRE_REMOVE => 'onPreRemove',
            UserEvents::SWITCH_THEME => 'onSwitchTheme',
        ];
    }

    /**
     * @param GenericEvent $event
     */
    public function onSync(GenericEvent $event)
    {
        $user = $event->getSubject();
        if (!($user instanceof User)) {
            return;
        }

        $this->client->get($user);
    }

    /**
     * @param GenericEvent $event
     *
     * @throws \Exception
     */
    public function onPreRemove(GenericEvent $event)
    {
        $user = $event->getSubject();
        if (!($user instanceof User)) {
            return;
        }

        try {
            $this
                ->workspaceMemberClient
                ->delete(
                    $this->teamContext->getCurrentSlug(),
                    $user->getEmail(),
                    $this->userContext->getUser()
                )
            ;
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
            if (404 === $response->getStatusCode()) {
                return;
            }

            throw $e;
        }
    }

    /**
     * @param GenericEvent $event
     */
    public function onSwitchTheme(GenericEvent $event)
    {
        $user = $event->getSubject();
        if (!($user instanceof User)) {
            return;
        }

        $this->client->update($user, ['theme' => $user->getTheme()]);
    }
}
