<?php

namespace Component\Form\Extension\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * The BooleanToStringTransformer is quite spastic some times and fucks up with "multipart/form-data" OR PATCH requests.
 */
class BooleanToStringTransformer implements DataTransformerInterface
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
        if (null === $value) {
            return;
        }

        if (!is_bool($value)) {
            throw new TransformationFailedException('Expected a Boolean.');
        }

        return $value ? $this->trueValue : null;
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

        if (!is_string($value)) {
            throw new TransformationFailedException('Expected a string.');
        }

        $comparisons = [
            $value === 1,
            $value === '1',
            $value === true,
            $value === $this->trueValue,
        ];

        return in_array(true, $comparisons, true);
    }
}
