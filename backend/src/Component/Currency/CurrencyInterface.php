<?php

namespace Component\Currency;

use Component\Model\CodeAwareInterface;
use Component\Model\TimestampableInterface;

interface CurrencyInterface extends CodeAwareInterface, TimestampableInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getSymbol(): string;
}
