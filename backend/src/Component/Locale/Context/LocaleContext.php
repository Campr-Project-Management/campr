<?php

namespace Component\Locale\Context;

use AppBundle\Entity\User;
use Component\Locale\Provider\LocaleProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LocaleContext implements LocaleContextInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var LocaleProviderInterface
     */
    private $localeProvider;

    /**
     * LocaleContext constructor.
     *
     * @param TokenStorageInterface   $tokenStorage
     * @param LocaleProviderInterface $localeProvider
     * @param RequestStack            $requestStack
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        LocaleProviderInterface $localeProvider,
        RequestStack $requestStack
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
        $this->localeProvider = $localeProvider;
    }

    /**
     * @return string
     */
    public function getLocaleCode(): string
    {
        $user = $this->getUser();
        if ($user) {
            return $user->getLocale();
        }

        $request = $this->getRequest();
        if (!$request) {
            return $this->getDefaultLocaleCode();
        }

        $locale = $request->attributes->get('_locale');
        if (!empty($locale) && in_array($locale, $this->localeProvider->getAvailableLocalesCodes())) {
            return $locale;
        }

        $locale = $request->cookies->get(LocaleContextInterface::STORAGE_KEY, $this->getDefaultLocaleCode());
        if (in_array($locale, $this->localeProvider->getAvailableLocalesCodes())) {
            return $locale;
        }

        return $this->getDefaultLocaleCode();
    }

    /**
     * @return Request|null
     */
    private function getRequest()
    {
        return $this->requestStack->getMasterRequest();
    }

    /**
     * @return User|null
     */
    private function getUser()
    {
        $token = $this->tokenStorage->getToken();
        if (!$token) {
            return null;
        }

        $user = $token->getUser();
        if ($user instanceof User) {
            return $user;
        }

        return null;
    }

    /**
     * @return string
     */
    private function getDefaultLocaleCode(): string
    {
        return $this->localeProvider->getDefaultLocaleCode();
    }
}
