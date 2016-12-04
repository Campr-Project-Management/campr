<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Communication;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCommunicationData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $project = $this->getReference('project1');
        $participant1 = $this->getReference('user3');
        $participant2 = $this->getReference('user4');
        $schedule = $this->getReference('schedule1');

        $communication = (new Communication())
            ->setProject($project)
            ->setMeetingName('communication1')
            ->setLocation('location')
            ->setContent('content')
            ->setSchedule($schedule)
            ->addParticipant($participant1)
            ->addParticipant($participant2)
        ;

        $manager->persist($communication);
        $manager->flush();
    }

    /**
     * return int.
     */
    public function getOrder()
    {
        return 3;
    }
}
