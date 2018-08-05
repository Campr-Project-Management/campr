<?php

namespace AppBundle\Twig;

use Component\Locale\Context\LocaleContextInterface;
use Component\Locale\Provider\LocaleProviderInterface;

class LocaleExtension extends \Twig_Extension
{
    /**
     * @var LocaleProviderInterface
     */
    private $localeProvider;

    /**
     * @var LocaleContextInterface
     */
    private $localeContext;

    /**
     * LocaleExtension constructor.
     *
     * @param LocaleContextInterface  $localeContext
     * @param LocaleProviderInterface $localeProvider
     */
    public function __construct(LocaleContextInterface $localeContext, LocaleProviderInterface $localeProvider)
    {
        $this->localeContext = $localeContext;
        $this->localeProvider = $localeProvider;
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('app_available_locales', [$this, 'getLocales']),
            new \Twig_SimpleFunction('app_locale', [$this, 'getLocale']),
        ];
    }

    /**
     * @return array
     */
    public function getLocales(): array
    {
        return $this->localeProvider->getAvailableLocalesCodes();
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->localeContext->getLocaleCode();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'app_locale_extension';
    }
}
