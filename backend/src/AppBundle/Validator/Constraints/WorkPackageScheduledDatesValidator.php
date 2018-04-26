<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\WorkPackage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class WorkPackageScheduledDatesValidator extends ConstraintValidator
{
    /**
     * @param WorkPackage                          $wp         The value that should be validated
     * @param WorkPackageScheduledDates|Constraint $constraint The constraint for the validation
     */
    public function validate($wp, Constraint $constraint)
    {
        if (!$wp->getScheduledStartAt() || !$wp->getScheduledFinishAt()) {
            return;
        }

        if ($wp->getScheduledFinishAt() < $wp->getScheduledStartAt()) {
            $this->context
                ->buildViolation($constraint->greaterThanOrEqualFinishAtMessage)
                ->atPath('scheduledFinishAt')
                ->addViolation()
            ;
        }

        $parent = $wp->getParent();
        if (!$parent) {
            return;
        }

        if ($parent->getScheduledStartAt() && $wp->getScheduledStartAt() < $parent->getScheduledStartAt()) {
            $this->context
                ->buildViolation(
                    $constraint->invalidStartAtMessage,
                    [
                        '%date%' => $wp->getScheduledStartAt()->format('d.m.Y'),
                        '%parent%' => $parent->getScheduledStartAt()->format('d.m.Y'),
                    ]
                )
                ->atPath('scheduledStartAt')
                ->addViolation()
            ;
        }

        if ($parent->getScheduledFinishAt() && $wp->getScheduledFinishAt() > $parent->getScheduledFinishAt()) {
            $this->context
                ->buildViolation(
                    $constraint->invalidFinishAtMessage,
                    [
                        '%date%' => $wp->getScheduledFinishAt()->format('d.m.Y'),
                        '%parent%' => $parent->getScheduledFinishAt()->format('d.m.Y'),
                    ]
                )
                ->atPath('scheduledFinishAt')
                ->addViolation()
            ;
        }
    }
}
