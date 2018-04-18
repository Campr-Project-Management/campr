<?php

namespace AppBundle\Twig;

use AppBundle\Templating\Helper\FormatMoneyHelper;

class FormatMoneyExtension extends \Twig_Extension
{
    /**
     * @var FormatMoneyHelper
     */
    private $helper;

    /**
     * FormatMoneyExtension constructor.
     *
     * @param FormatMoneyHelper $helper
     */
    public function __construct(FormatMoneyHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            new \Twig_SimpleFilter('app_format_money', [$this->helper, 'formatAmount']),
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'app_format_money_extension';
    }
}
