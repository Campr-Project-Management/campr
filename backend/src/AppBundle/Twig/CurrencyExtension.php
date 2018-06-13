<?php

namespace AppBundle\Twig;

use Component\Currency\Model\CurrencyAwareInterface;
use Component\Currency\Model\CurrencyInterface;
use Component\Project\ProjectAwareInterface;
use Component\Project\ProjectInterface;

class CurrencyExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            new \Twig_SimpleFilter('app_currency_symbol', [$this, 'getCurrencySymbol']),
            new \Twig_SimpleFilter('app_currency_code', [$this, 'getCurrencyCode']),
            new \Twig_SimpleFilter('app_currency', [$this, 'getCurrency']),
        ];
    }

    /**
     * @param CurrencyAwareInterface|CurrencyInterface|ProjectInterface $value
     *
     * @return string
     */
    public function getCurrencySymbol($value): string
    {
        $currency = $this->getCurrency($value);
        if (!$currency) {
            return '';
        }

        return $currency->getSymbol();
    }

    public function getCurrencyCode($value): string
    {
        $currency = $this->getCurrency($value);
        if (!$currency) {
            return '';
        }

        return $currency->getCode();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'app_currency_extension';
    }

    /**
     * @param CurrencyAwareInterface|CurrencyInterface|ProjectInterface $value
     *
     * @return CurrencyInterface|null
     */
    public function getCurrency($value)
    {
        if ($value instanceof CurrencyInterface) {
            return $value;
        }

        if ($value instanceof CurrencyAwareInterface) {
            return $value->getCurrency();
        }

        if ($value instanceof ProjectAwareInterface) {
            return $this->getCurrency($value->getProject());
        }

        return null;
    }
}
