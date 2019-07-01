<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Sequence extends Constraint
{
    public $messageNegative = 'greater_than_or_equal.sequence';
    public $messageUnique = 'unique.sequence';

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return SequenceValidator::class;
    }

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
