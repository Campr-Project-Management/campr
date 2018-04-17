<?php

namespace Component\Currency;

interface CurrencyAwareInterface
{
    /**
     * @param CurrencyInterface $currency
     */
    public function setCurrency(CurrencyInterface $currency);

    /**
     * @return CurrencyInterface
     */
    public function getCurrency();
}
