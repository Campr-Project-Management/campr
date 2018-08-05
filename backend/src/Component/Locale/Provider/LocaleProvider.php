<?php

namespace Component\Locale\Provider;

class LocaleProvider implements LocaleProviderInterface
{
    /**
     * @var string
     */
    private $defaultLocaleCode;

    /**
     * @var array
     */
    private $availableLocalesCodes;

    /**
     * LocaleProvider constructor.
     *
     * @param string $defaultLocaleCode
     * @param array  $availableLocalesCodes
     */
    public function __construct(string $defaultLocaleCode, array $availableLocalesCodes)
    {
        $this->defaultLocaleCode = $defaultLocaleCode;
        $this->availableLocalesCodes = $availableLocalesCodes;
    }

    /**
     * @return array
     */
    public function getAvailableLocalesCodes(): array
    {
        return $this->availableLocalesCodes;
    }

    /**
     * @return string
     */
    public function getDefaultLocaleCode(): string
    {
        return $this->defaultLocaleCode;
    }
}
