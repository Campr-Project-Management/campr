<?php

namespace AppBundle\Services;

use AppBundle\Entity\User;
use AppBundle\Entity\WorkPackage;
use AppBundle\Utils\CsvResponse;
use AppBundle\Utils\IcsResponse;
use Doctrine\ORM\EntityManager;
use Jsvrcek\ICS\Model\Calendar;
use Jsvrcek\ICS\Model\CalendarEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CalendarService.
 *
 * Perform operations on calendars
 */
class CalendarService
{
    const EXPORT_TYPE_ICS = 'ics';
    const EXPORT_TYPE_CSV = 'csv';
    const CSV_HEADERS = [
        'Subject', 'Start Date', 'Start Time', 'End Date', 'End Time',
        'All Day Event', 'Description', 'Location', 'Private',
    ];

    private $em;

    /**
     * CalendarService constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param $options
     * @param User $user
     *
     * @return CsvResponse|IcsResponse|JsonResponse
     */
    public function exportUserCalendars($options, User $user)
    {
        $wps = $this
            ->em
            ->getRepository(WorkPackage::class)
            ->findUserFiltered($user, $options)
            ->getResult()
        ;

        if (empty($wps)) {
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        $data = [];
        if (isset($options['type']) && $options['type'] === self::EXPORT_TYPE_CSV) {
            $data[] = self::CSV_HEADERS;
            foreach ($wps as $wp) {
                $today = new \DateTime();
                $data[] = [
                    $wp->getName(),
                    $wp->getScheduledStartAt() ? $wp->getScheduledStartAt()->format('m/d/Y') : $today->format('m/d/Y'),
                    '',
                    $wp->getScheduledFinishAt() ? $wp->getScheduledFinishAt()->format('m/d/Y') : $today->format('m/d/Y'),
                    '',
                    $wp->getScheduledStartAt() && $wp->getScheduledFinishAt(),
                    $wp->getContent(),
                    '',
                    true,
                ];
            }

            $response = new CsvResponse($data, 'project_'.$wp->getProjectName().'_events.csv');

            return $response;
        } elseif (isset($options['type']) && $options['type'] === self::EXPORT_TYPE_ICS) {
            $data = [];
            foreach ($wps as $wp) {
                $wpCal = $wp->getCalendar();
                if ($wpCal) {
                    if (!array_key_exists($wpCal->getName(), $data)) {
                        $calendar = new Calendar();
                        $calendar->setProdId($wpCal->getName());
                        $data[$wpCal->getName()] = $calendar;
                    }
                    $data[$wpCal->getName()]->addEvent($this->createIcalEvent($wp));
                } else {
                    if (!array_key_exists('default', $data)) {
                        $calendar = new Calendar();
                        $calendar->setProdId('default');
                        $data['default'] = $calendar;
                    }
                    $data['default']->addEvent($this->createIcalEvent($wp));
                }
            }

            $response = new IcsResponse($data, 'project_'.$wp->getProjectName().'_events.ics');

            return $response;
        }

        return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param WorkPackage $wp
     *
     * @return CalendarEvent
     *
     * @throws \Jsvrcek\ICS\Exception\CalendarEventException
     */
    private function createIcalEvent(WorkPackage $wp)
    {
        $event = new CalendarEvent();
        $event->setSummary($wp->getName())
            ->setUid(strval($wp->getId()))  // Remove this field in order to use .ics files in Google Calendar
            ->setDescription(trim(preg_replace('/\s+/', ' ', $wp->getContent())))
            ->setAllDay($wp->getScheduledStartAt() && $wp->getScheduledFinishAt())
        ;
        $wp->getScheduledStartAt()
            ? $event->setStart($wp->getScheduledStartAt())
            : $event->setStart(new \DateTime())
        ;
        if ($wp->getScheduledFinishAt()) {
            $event->setEnd($wp->getScheduledFinishAt());
        }

        return $event;
    }
}
