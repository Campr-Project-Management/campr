<?php

namespace Component\Meeting;

use AppBundle\Entity\Meeting;
use Symfony\Component\EventDispatcher\Event;

class MeetingEvent extends Event
{
    /**
     * @var Meeting
     */
    private $meeting;

    /**
     * MeetingEvent constructor.
     *
     * @param Meeting $meeting
     */
    public function __construct(Meeting $meeting)
    {
        $this->meeting = $meeting;
    }

    /**
     * @return Meeting
     */
    public function getMeeting(): Meeting
    {
        return $this->meeting;
    }
}
