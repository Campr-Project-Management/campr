<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class MeetingAgendaDuration extends Constraint
{
    /**
     * @var string
     */
    public $isGreaterThanMeetingDuration = 'is_greater_than.meeting_duration';

    /**
     * @var string
     */
    public $invalidDurationMessage = 'invalid.meeting_agenda.duration';

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
