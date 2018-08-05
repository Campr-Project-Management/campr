<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Component\TrafficLight\TrafficLight as TL;

class TrafficLightValidator extends ConstraintValidator
{
    /**
     * @param int                     $value
     * @param Constraint|TrafficLight $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (in_array($value, TL::VALUES)) {
            return;
        }

        $this
            ->context
            ->buildViolation($constraint->message)
            ->atPath('trafficLight')
            ->addViolation()
        ;
    }
}
