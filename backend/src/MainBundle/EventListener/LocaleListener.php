<?php

namespace MainBundle\EventListener;

use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class LocaleListener.
 */
class LocaleListener
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * LocaleListener constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param TranslatorInterface   $translator
     * @param string                $defaultLocale
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        TranslatorInterface $translator,
        $defaultLocale = 'en'
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->translator = $translator;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $locale = $this->getLocale($request);
        $this->translator->setLocale($locale);
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    private function getLocale(Request $request)
    {
        if (!$request->hasPreviousSession()) {
            return $this->defaultLocale;
        }

        if (!$this->tokenStorage->getToken()) {
            return $this->defaultLocale;
        }

        $user = $this->tokenStorage->getToken()->getUser();

        if (!($user instanceof User)) {
            return $this->defaultLocale;
        }

        return $user->getLocale();
    }
}
