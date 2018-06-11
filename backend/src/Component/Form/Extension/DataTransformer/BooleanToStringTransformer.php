<?php

namespace Component\Form\Extension\DataTransformer;

use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\DataTransformer\BooleanToStringTransformer as BTST;

/**
 * The BooleanToStringTransformer is quite spastic some times and fucks up with "multipart/form-data" OR PATCH requests.
 */
class BooleanToStringTransformer extends BTST
{
    /**
     * @var string
     */
    protected $trueValue;

    /**
     * @param string $trueValue
     */
    public function __construct($trueValue)
    {
        $this->trueValue = $trueValue;
    }

    /**
     * @param bool $value
     *
     * @return string
     */
    public function transform($value)
    {
        return parent::transform($value);
    }

    /**
     * @param string $value
     *
     * @return bool|string
     */
    public function reverseTransform($value)
    {
        if (null === $value) {
            return false;
        }

        if (is_bool($value)) {
            return $value;
        }

        if (!is_string($value)) {
            throw new TransformationFailedException('Expected a string.');
        }

        switch ($value) {
            case 0:
            case '0':
            case 'false':
                return false;
            case 1:
            case '1':
            case 'true':
                return true;
            default:
                return $value === $this->trueValue;
        }
    }
}
