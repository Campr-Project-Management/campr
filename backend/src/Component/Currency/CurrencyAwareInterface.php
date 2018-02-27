<?php

namespace Component\Currency;

interface CurrencyAwareInterface
{
    /**
     * @param string $currency
     */
    public function setCurrency(string $currency);

    /**
     * @return string
     */
    public function getCurrency(): string;
}
