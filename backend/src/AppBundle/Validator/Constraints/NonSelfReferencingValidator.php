<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NonSelfReferencingValidator extends ConstraintValidator
{
    /**
     * @param mixed                         $value
     * @param NonSelfReferencing|Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value) {
            return;
        }

        $object = $this->context->getObject();
        if ($object !== $value) {
            return;
        }

        $this->context
            ->buildViolation($constraint->message)
            ->addViolation()
        ;
    }
}
