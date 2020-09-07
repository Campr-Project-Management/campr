<?php

namespace MainBundle\EventListener;

use Component\Locale\Context\LocaleContextInterface;
use Component\Locale\Provider\LocaleProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class LocaleListener.
 */
class LocaleListener implements EventSubscriberInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var LocaleProviderInterface
     */
    private $localeProvider;

    /**
     * @var LocaleContextInterface
     */
    private $localeContext;

    /**
     * LocaleListener constructor.
     * @param TranslatorInterface $translator
     * @param LocaleContextInterface $localeContext
     * @param LocaleProviderInterface $localeProvider
     * @param ContainerInterface $container
     */
    public function __construct(
        TranslatorInterface $translator,
        LocaleContextInterface $localeContext,
        LocaleProviderInterface $localeProvider,
        ContainerInterface $container
    ) {
        $this->translator = $translator;
        $this->localeContext = $localeContext;
        $this->localeProvider = $localeProvider;

        $mainDomain = $container->getParameter('domain');
        if ($_SERVER['HTTP_HOST'] != $mainDomain) {
            setcookie("domainBeforeRedirect", $_SERVER['HTTP_HOST'], time()+3600, '/', ".{$mainDomain}");
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 4],
        ];
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $locale = $this->localeContext->getLocaleCode();

        $request->setLocale($locale);
        $request->setDefaultLocale($this->localeProvider->getDefaultLocaleCode());
        $request->attributes->set('_locale', $this->localeContext->getLocaleCode());
        $request->getSession()->set('_locale', $this->localeContext->getLocaleCode());

        $this->translator->setLocale($locale);
    }
}
