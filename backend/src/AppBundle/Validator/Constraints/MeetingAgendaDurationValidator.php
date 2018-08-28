<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\MeetingAgenda;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MeetingAgendaDurationValidator extends ConstraintValidator
{
    /**
     * @param MeetingAgenda                    $agenda     The value that should be validated
     * @param MeetingAgendaDuration|Constraint $constraint The constraint for the validation
     */
    public function validate($agenda, Constraint $constraint)
    {
        $meeting = $agenda->getMeeting();

        if (!$meeting) {
            return;
        }

        $meetingDuration = ($meeting->getEnd()->getTimestamp() - $meeting->getStart()->getTimestamp()) / 60;

        $meetingAgendas = $meeting->getMeetingAgendas();
        if (count($meetingAgendas) > 0) {
            $duration = 0;
            foreach ($meetingAgendas as $meetingAgenda) {
                $duration += $meetingAgenda->getDuration();
            }
            if ($agenda->getDuration() > 0) {
                if ($duration > $meetingDuration) {
                    $this->context
                        ->buildViolation(
                            $constraint->isGreaterThanMeetingDuration,
                            [
                                '%duration%' => $meetingDuration,
                            ]
                        )
                        ->atPath('duration')
                        ->addViolation()
                    ;
                }
            }
        }
    }
}
