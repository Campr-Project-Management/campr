<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\WorkPackage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class WorkPackageNonSelfReferencingValidator extends ConstraintValidator
{
    /**
     * @param WorkPackage                   $value
     * @param WorkPackageNonSelfReferencing $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!($value instanceof WorkPackage)) {
            return;
        }

        if ($value === $value->getParent()) {
            $this->context
                ->buildViolation($constraint->parent)
                ->atPath('parent')
                ->addViolation()
            ;
        }

        if ($value === $value->getPhase()) {
            $this->context
                ->buildViolation($constraint->phase)
                ->atPath('phase')
                ->addViolation()
            ;
        }

        if ($value === $value->getMilestone()) {
            $this->context
                ->buildViolation($constraint->milestone)
                ->atPath('milestone')
                ->addViolation()
            ;
        }
    }
}
