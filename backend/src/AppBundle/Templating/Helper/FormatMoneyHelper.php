<?php

namespace AppBundle\Templating\Helper;

use AppBundle\Formatter\MoneyFormatter;
use Symfony\Component\Templating\Helper\Helper;

class FormatMoneyHelper extends Helper
{
    /**
     * @var MoneyFormatter
     */
    private $moneyFormatter;

    /**
     * FormatMoneyHelper constructor.
     *
     * @param MoneyFormatter $moneyFormatter
     */
    public function __construct(MoneyFormatter $moneyFormatter)
    {
        $this->moneyFormatter = $moneyFormatter;
    }

    /**
     * @param float  $amount
     * @param string $currencyCode
     * @param string $localeCode
     *
     * @return string
     */
    public function formatAmount(float $amount, string $currencyCode, string $localeCode = null): string
    {
        return $this->moneyFormatter->format($amount, $currencyCode, $localeCode);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'app_format_money';
    }
}
