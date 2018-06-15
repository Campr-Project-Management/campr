<?php

namespace Component\Currency\Model;

use Component\Resource\Model\CodeAwareInterface;
use Component\Resource\Model\TimestampableInterface;
use Component\Resource\Model\ResourceInterface;

interface CurrencyInterface extends CodeAwareInterface, TimestampableInterface, ResourceInterface
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
