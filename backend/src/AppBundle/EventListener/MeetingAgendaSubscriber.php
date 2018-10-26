<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\MeetingAgenda;
use Component\MeetingAgenda\MeetingAgendaEvent;
use Component\MeetingAgenda\MeetingAgendaEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MeetingAgendaSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            MeetingAgendaEvents::CALCULATE_START_DATE => 'onCalculateStartDate',
        ];
    }

    public function onCalculateStartDate(MeetingAgendaEvent $event)
    {
        $agenda = $event->getMeetingAgenda();

        $this->setMeetingAgendaStartDate($agenda);
    }

    public function setMeetingAgendaStartDate(MeetingAgenda $meetingAgenda)
    {
        $meeting = $meetingAgenda->getMeeting();
        if ($meeting) {
            $agendas = $meeting->getMeetingAgendas();
            $start = $meeting->getStart();
            $duration = 0;
            foreach ($agendas as $key => $agenda) {
                $duration += $agenda->getDuration();
            }
            $start = clone $start;
            $start = $start->modify("+{$duration} minutes");
            $meetingAgenda->setStart($start);
        }
    }
}
