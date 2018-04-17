<?php

namespace AppBundle\Formatter;

use Webmozart\Assert\Assert;

final class MoneyFormatter
{
    /**
     * @param int         $amount
     * @param string      $currency
     * @param null|string $locale
     *
     * @return string
     */
    public function format(int $amount, string $currency, string $locale = null): string
    {
        $formatter = new \NumberFormatter($locale ?? 'en', \NumberFormatter::CURRENCY);
        $result = $formatter->formatCurrency(abs($amount), $currency);

        Assert::notSame(
            false,
            $result,
            sprintf('The amount "%s" of type %s cannot be formatted to currency "%s".', $amount, gettype($amount), $currency)
        );

        return $amount >= 0 ? $result : '-'.$result;
    }
}
