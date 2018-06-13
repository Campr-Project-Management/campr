<?php

namespace Component\Currency\Model;

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
