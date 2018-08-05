<?php

namespace AppBundle\Validator\Constraints;

/**
 * @Annotation
 */
class GreaterThan extends AbstractComparison
{
    const TOO_LOW_ERROR = '778b7ae0-84d3-481a-9dec-35fdb64b1d78';

    protected static $errorNames = array(
        self::TOO_LOW_ERROR => 'TOO_LOW_ERROR',
    );

    public $message = 'This value should be greater than {{ compared_value }}.';
}
