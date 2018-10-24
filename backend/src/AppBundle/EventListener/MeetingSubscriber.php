<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Meeting;
use Component\Meeting\MeetingEvent;
use Component\Meeting\MeetingEvents;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MeetingSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * MeetingAgendaSubscriber constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return [
            MeetingEvents::CALCULATE_MEETING_AGENDA_START_DATES => 'onCalculateMeetingAgendaStartDates',
            MeetingEvents::RECALCULATE_MEETING_AGENDA_START_DATES => 'onRecalculateMeetingAgendaStartDates',
        ];
    }

    public function onCalculateMeetingAgendaStartDates(MeetingEvent $event)
    {
        $meeting = $event->getMeeting();

        $this->setMeetingAgendasStartDate($meeting);
    }

    public function onRecalculateMeetingAgendaStartDates(MeetingEvent $event)
    {
        $meeting = $event->getMeeting();

        $this->updateMeetingAgendasStartDates($meeting);
    }

    private function updateMeetingAgendasStartDates(Meeting $meeting)
    {
        $agendas = $meeting->getMeetingAgendas();
        $start = $meeting->getStart();
        $duration = 0;
        foreach ($agendas as $key => $agenda) {
            $start = clone $start;
            $start = $start->modify("+{$duration} minutes");
            $agenda->setStart($start);
            $duration += $agenda->getDuration();
            $this->em->persist($agenda);
        }
    }

    private function setMeetingAgendasStartDate(Meeting $meeting)
    {
        $agendas = $meeting->getMeetingAgendas();
        $start = $meeting->getStart();
        $duration = 0;
        foreach ($agendas as $key => $agenda) {
            $start = clone $start;
            $start = $start->modify("+{$duration} minutes");
            $agenda->setStart($start);
            $duration += $agenda->getDuration();
        }
    }
}
