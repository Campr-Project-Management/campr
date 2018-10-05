<?php

namespace Component\Locale\Provider;

interface LocaleProviderInterface
{
    /**
     * @return array
     */
    public function getAvailableLocalesCodes(): array;

    /**
     * @return string
     */
    public function getDefaultLocaleCode(): string;
}
