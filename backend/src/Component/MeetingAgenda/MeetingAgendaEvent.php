<?php

namespace Component\MeetingAgenda;

use AppBundle\Entity\MeetingAgenda;
use Symfony\Component\EventDispatcher\Event;

class MeetingAgendaEvent extends Event
{
    /**
     * @var MeetingAgenda
     */
    private $meetingAgenda;

    /**
     * MeetingAgendaEvent constructor.
     *
     * @param MeetingAgenda $meetingAgenda
     */
    public function __construct(MeetingAgenda $meetingAgenda)
    {
        $this->meetingAgenda = $meetingAgenda;
    }

    /**
     * @return MeetingAgenda
     */
    public function getMeetingAgenda(): MeetingAgenda
    {
        return $this->meetingAgenda;
    }
}
