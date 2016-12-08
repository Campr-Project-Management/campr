<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\MeetingAgenda;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for MeetingAgenda entity.
 */
class LoadMeetingAgendaData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $meeting = $this->getReference('meeting1');
        $responsible = $this->getReference('user3');
        $start = new \DateTime();
        $end = new \DateTime('+1 hour');
        $diff = $end->diff($start)->format('%H:%I:%S');

        $meetingAgenda1 = (new MeetingAgenda())
            ->setResponsibility($responsible)
            ->setTopic('topic1')
            ->setMeeting($meeting)
            ->setStart($start)
            ->setEnd($end)
            ->setDuration(new \DateTime($diff))
        ;
        $manager->persist($meetingAgenda1);

        $start = new \DateTime('+1 hour');
        $end = new \DateTime('+3 hours');
        $diff = $end->diff($start)->format('%H:%I:%S');

        $meetingAgenda2 = (new MeetingAgenda())
            ->setResponsibility($responsible)
            ->setTopic('topic2')
            ->setMeeting($meeting)
            ->setStart($start)
            ->setEnd($end)
            ->setDuration(new \DateTime($diff))
        ;
        $manager->persist($meetingAgenda2);

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 4;
    }
}
