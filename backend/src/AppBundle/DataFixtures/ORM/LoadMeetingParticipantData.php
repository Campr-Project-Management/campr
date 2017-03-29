<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\MeetingParticipant;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for MeetingParticipant entity.
 */
class LoadMeetingParticipantData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user4 = $this->getReference('user4');
        $user5 = $this->getReference('user5');
        $meeting = $this->getReference('meeting1');

        $meetingParticipant1 = (new MeetingParticipant())
            ->setUser($user4)
            ->setMeeting($meeting)
        ;
        $manager->persist($meetingParticipant1);

        $meetingParticipant2 = (new MeetingParticipant())
            ->setUser($user5)
            ->setMeeting($meeting)
        ;
        $manager->persist($meetingParticipant2);

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
