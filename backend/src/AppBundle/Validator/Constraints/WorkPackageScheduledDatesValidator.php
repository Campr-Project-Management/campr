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

        $phase = $wp->getPhase();

        if ($phase->getScheduledStartAt() > $wp->getScheduledStartAt()) {
            $this->context
                ->buildViolation(
                    $constraint->greaterStartedAtWitPhaseMessage,
                    [
                        '%date%' => $wp->getScheduledStartAt()->format('d.m.Y'),
                        '%phase_name%' => $phase->getName(),
                        '%phase_start%' => $phase->getScheduledStartAt()->format('d.m.Y'),
                        '%phase_end%' => $phase->getScheduledFinishAt()->format('d.m.Y'),
                    ]
                )
                ->atPath('scheduledStartAt')
                ->addViolation();
        }

        if ($phase->getScheduledFinishAt() < $wp->getScheduledFinishAt()) {
            $this->context
                ->buildViolation(
                    $constraint->greaterFinishedAtWitPhaseMessage,
                    [
                        '%date%' => $wp->getScheduledFinishAt()->format('d.m.Y'),
                        '%phase_name%' => $phase->getName(),
                        '%phase_start%' => $phase->getScheduledStartAt()->format('d.m.Y'),
                        '%phase_end%' => $phase->getScheduledFinishAt()->format('d.m.Y'),
                    ]
                )
                ->atPath('scheduledFinishAt')
                ->addViolation();
        }

        $milestone = $wp->getMilestone();

        if (isset($milestone) && ($milestone->getScheduledFinishAt() < $wp->getScheduledFinishAt())) {
            $this->context
                ->buildViolation(
                    $constraint->greaterFinishedAtWitMilestoneMessage,
                    [
                        '%date%' => $wp->getScheduledFinishAt()->format('d.m.Y'),
                        '%milestone_name%' => $milestone->getName(),
                        '%milestone_end%' => $milestone->getScheduledFinishAt()->format('d.m.Y'),
                    ]
                )
                ->atPath('scheduledFinishAt')
                ->addViolation();
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
