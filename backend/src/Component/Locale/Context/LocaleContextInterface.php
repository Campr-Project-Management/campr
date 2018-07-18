<?php

namespace Component\Locale\Context;

interface LocaleContextInterface
{
    const STORAGE_KEY = 'locale';

    const TTL = 60 * 60 * 24 * 365;

    /**
     * @return string
     */
    public function getLocaleCode(): string;
}
