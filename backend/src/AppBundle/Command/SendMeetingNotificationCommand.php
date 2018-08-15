<?php

namespace AppBundle\Command;

use AppBundle\Entity\Meeting;
use AppBundle\Entity\MeetingParticipant;
use AppBundle\Entity\User;
use AppBundle\Services\MailerService;
use Component\PDF\Exception\PDFException;
use Doctrine\Common\Collections\Collection;
use Jsvrcek\ICS\CalendarExport;
use Jsvrcek\ICS\CalendarStream;
use Jsvrcek\ICS\Model\Calendar;
use Jsvrcek\ICS\Model\CalendarEvent;
use Jsvrcek\ICS\Model\Description\Location;
use Jsvrcek\ICS\Model\Relationship\Attendee;
use Jsvrcek\ICS\Model\Relationship\Organizer;
use Jsvrcek\ICS\Utility\Formatter;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Webmozart\Assert\Assert;

class SendMeetingNotificationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:meeting:send-notification')
            ->addArgument('meetingId', InputArgument::REQUIRED, 'Meeting ID', null)
            ->addArgument('userId', InputArgument::REQUIRED, 'User ID', null)
            ->addArgument('host', InputArgument::REQUIRED, 'Hostname', null)
            ->addArgument('scheme', InputArgument::REQUIRED, 'Scheme', null)
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $meetingId = (int) $input->getArgument('meetingId');
        $userId = (int) $input->getArgument('userId');
        $host = $input->getArgument('host');
        $scheme = $input->getArgument('scheme');

        $meeting = $this->findMeeting($meetingId);
        $user = $this->findUser($userId);

        $io = new SymfonyStyle($input, $output);

        $recipients = $this->getMailRecipientsGroupedByLocale($meeting);
        if (empty($recipients)) {
            $io->warning('No email recipients found.');

            return 0;
        }

        $mailer = $this->getMailer();

        $io->note('Creating meeting ICS...');
        $icsAttachment = $this->createMailIcsAttachement($meeting, $user);
        $io->success('Meeting ICS successfully created');

        $io->note('Creating meeting PDF...');
        $attachment = $this->createMailPDFAttachment($meeting, $user, $host);
        $io->success('Meeting PDF successfully created');

        $io->note(
            sprintf(
                'Emailing to: %s',
                implode(
                    ', ',
                    array_map(
                        function ($r) {
                            return implode(',', $r);
                        },
                        $recipients
                    )
                )
            )
        );

        $mailer->addFromParameter(
            $user->getEmail(),
            [
                'name' => $user->getFullName(),
                'email' => $user->getEmail(),
            ]
        );

        $url = $scheme.'://'.$host.'/#/projects/'.$meeting->getProject()->getId().'/meetings/view-meeting/'.$meeting->getId();
        $trans = $this->getContainer()->get('translator');
        $currentLocale = $trans->getLocale();
        foreach ($recipients as $locale => $to) {
            $trans->setLocale($locale);

            $mailer->sendEmail(
                ':meeting:notification.html.twig',
                $user->getEmail(),
                $to,
                ['meeting' => $meeting, 'url' => $url],
                [$attachment, $icsAttachment]
            );
        }

        $trans->setLocale($currentLocale);

        $io->success('Email successfully sent');

        return 0;
    }

    /**
     * @param Meeting $meeting
     *
     * @return array
     */
    private function getMailRecipientsGroupedByLocale(Meeting $meeting): array
    {
        /** @var User[] $users */
        $users = $this
            ->getMeetingParticipants($meeting)
            ->map(
                function (MeetingParticipant $meetingParticipant) {
                    return $meetingParticipant->getUser();
                }
            )
        ;

        $recipients = [];
        foreach ($users as $user) {
            $locale = $user->getLocale();
            if (empty($recipients[$locale])) {
                $recipients[$locale] = [];
            }

            $recipients[$locale][] = $user->getEmail();
        }

        return $recipients;
    }

    /**
     * @param int $id
     *
     * @return Meeting
     */
    private function findMeeting(int $id): Meeting
    {
        $meeting = $this
            ->getContainer()
            ->get('app.repository.meeting')
            ->find($id)
        ;
        Assert::notNull($meeting, sprintf('Meeting with ID "%d" not found', $id));

        return $meeting;
    }

    /**
     * @param int $id
     *
     * @return User
     */
    private function findUser(int $id): User
    {
        $user = $this
            ->getContainer()
            ->get('app.repository.user')
            ->find($id)
        ;
        Assert::notNull($user, sprintf('User with ID "%d" not found', $id));

        return $user;
    }

    /**
     * @param Meeting $meeting
     * @param User    $user
     * @param string  $host
     *
     * @throws PDFException
     *
     * @return \Swift_Attachment
     */
    private function createMailPDFAttachment(Meeting $meeting, User $user, string $host): \Swift_Attachment
    {
        $pdf = $this->getPDFContent($meeting, $user, $host);

        return new \Swift_Attachment(
            $pdf,
            sprintf('status-report-%s.pdf', $meeting->getCreatedAt()->format('Y-m-d')),
            'application/pdf'
        );
    }

    /**
     * @param Meeting $meeting
     * @param User    $user
     *
     * @return \Swift_Attachment
     *
     * @throws \Jsvrcek\ICS\Exception\CalendarEventException
     */
    private function createMailIcsAttachement(Meeting $meeting, User $user): \Swift_Attachment
    {
        $ics = $this->getICSContent($meeting, $user);

        return new \Swift_Attachment(
            $ics,
            sprintf('meeting-event-%s.ics', $meeting->getCreatedAt()->format('Y-m-d')),
            'text/calendar'
        );
    }

    /**
     * @param Meeting $meeting
     * @param User    $user
     *
     * @return string
     *
     * @throws \Jsvrcek\ICS\Exception\CalendarEventException
     */
    private function getICSContent(Meeting $meeting, User $user): string
    {
        $calendar = new Calendar();
        $calendar->setProdId('default');
        $calendar->addEvent($this->createIcalEvent($meeting, $user));

        $calendarExport = new CalendarExport(new CalendarStream(), new Formatter());
        $calendarExport->addCalendar($calendar);

        return $calendarExport->getStream();
    }

    /**
     * @param Meeting $meeting
     * @param User    $user
     *
     * @return CalendarEvent
     *
     * @throws \Jsvrcek\ICS\Exception\CalendarEventException
     */
    private function createIcalEvent(Meeting $meeting, User $user)
    {
        $meetingDate = $meeting->getDate();
        $meetingStart = new \DateTime($meetingDate->format('Y-m-d').' '.$meeting->getStart()->format('H:i:s'));
        $meetingEnd = new \DateTime($meetingDate->format('Y-m-d').' '.$meeting->getEnd()->format('H:i:s'));

        $location = new Location();
        $location->setName($meeting->getLocation());

        $event = new CalendarEvent();
        $event->setSummary($meeting->getName());
        $event->setStart($meetingStart);
        $event->setEnd($meetingEnd);
        $event->addLocation($location);

        $recipients = $this
            ->getMeetingParticipants($meeting)
            ->map(
                function (MeetingParticipant $meetingParticipant) {
                    return $meetingParticipant->getUser();
                }
            )
        ;

        if (!empty($recipients)) {
            foreach ($recipients as $recipient) {
                $attendee = new Attendee(new Formatter());
                $attendee
                    ->setName($recipient->getFirstName().' '.$recipient->getLastName())
                    ->setValue($recipient->getEmail());
                $event->addAttendee($attendee);
            }
        }

        $organizer = new Organizer(new Formatter());
        $organizer->setValue($user->getEmail())
            ->setName($user->getFirstName().' '.$user->getLastName());
        $event->setOrganizer($organizer);

        return $event;
    }

    /**
     * @param Meeting $meeting
     * @param User    $user
     * @param string  $host
     *
     * @throws PDFException
     *
     * @return string
     */
    private function getPDFContent(Meeting $meeting, User $user, string $host): string
    {
        return $this
            ->getContainer()
            ->get('app.pdf.builder.meeting')
            ->setMeeting($meeting)
            ->setUser($user)
            ->setHost($host)
            ->getPrinter()
            ->getContents()
        ;
    }

    /**
     * @return MailerService
     */
    private function getMailer(): MailerService
    {
        return $this->getContainer()->get('app.service.mailer');
    }

    /**
     * @param Meeting $meeting
     *
     * @return Collection|MeetingParticipant[]
     */
    private function getMeetingParticipants(Meeting $meeting)
    {
        return $meeting
            ->getMeetingParticipants()
            ->filter(
                function (MeetingParticipant $meetingParticipant) {
                    return $meetingParticipant->getInDistributionList() && $meetingParticipant->getUser();
                }
            )
        ;
    }
}
