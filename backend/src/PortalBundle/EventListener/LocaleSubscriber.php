<?php

namespace PortalBundle\EventListener;

use AppBundle\Entity\User;
use Component\Locale\LocaleEvents;
use PortalBundle\Client\Http\UserClient;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LocaleSubscriber implements EventSubscriberInterface
{
    /**
     * @var array
     */
    private $locales;

    /**
     * @var UserClient
     */
    private $client;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * LocaleSubscriber constructor.
     *
     * @param array                 $locales
     * @param UserClient            $client
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(array $locales, UserClient $client, TokenStorageInterface $tokenStorage)
    {
        $this->locales = $locales;
        $this->client = $client;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            LocaleEvents::SWITCH => 'onLocaleSwitch',
        ];
    }

    /**
     * @param GenericEvent $event
     */
    public function onLocaleSwitch(GenericEvent $event)
    {
        $localeCode = $event->getSubject();
        if (empty($localeCode) || !in_array($localeCode, $this->locales) || !$this->getUser()) {
            return;
        }

        $this->client->update($this->getUser(), ['locale' => $localeCode]);
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        $token = $this->tokenStorage->getToken();
        if (!$token || !($token->getUser() instanceof User)) {
            return null;
        }

        return $token->getUser();
    }
}
