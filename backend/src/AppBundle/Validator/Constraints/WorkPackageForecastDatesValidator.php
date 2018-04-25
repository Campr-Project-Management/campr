<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\WorkPackage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class WorkPackageForecastDatesValidator extends ConstraintValidator
{
    /**
     * @param WorkPackage                         $wp         The value that should be validated
     * @param WorkPackageForecastDates|Constraint $constraint The constraint for the validation
     */
    public function validate($wp, Constraint $constraint)
    {
        if (!$wp->getForecastStartAt() || !$wp->getForecastFinishAt()) {
            return;
        }

        if ($wp->getForecastFinishAt() < $wp->getForecastStartAt()) {
            $this->context
                ->buildViolation($constraint->greaterThanOrEqualFinishAtMessage)
                ->atPath('forecastFinishAt')
                ->addViolation()
            ;
        }

        $parent = $wp->getParent();
        if (!$parent) {
            return;
        }

        if ($parent->getForecastStartAt() && $wp->getForecastStartAt() < $parent->getForecastStartAt()) {
            $this->context
                ->buildViolation(
                    $constraint->invalidStartAtMessage,
                    [
                        '%date%' => $wp->getForecastStartAt()->format('d.m.Y'),
                        '%parent%' => $parent->getForecastStartAt()->format('d.m.Y'),
                    ]
                )
                ->atPath('forecastStartAt')
                ->addViolation()
            ;
        }

        if ($parent->getForecastFinishAt() && $wp->getForecastFinishAt() > $parent->getForecastFinishAt()) {
            $this->context
                ->buildViolation(
                    $constraint->invalidFinishAtMessage,
                    [
                        '%date%' => $wp->getForecastFinishAt()->format('d.m.Y'),
                        '%parent%' => $parent->getForecastFinishAt()->format('d.m.Y'),
                    ]
                )
                ->atPath('forecastFinishAt')
                ->addViolation()
            ;
        }
    }
}
