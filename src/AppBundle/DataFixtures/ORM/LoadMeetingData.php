<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Meeting;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadMeetingData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $project = $this->getReference('project1');
        $date = new \DateTime();
        $start = new \DateTime();
        $end = new \DateTime('+1 hour');

        $meeting = (new Meeting())
            ->setName('meeting1')
            ->setLocation('location1')
            ->setObjectives('objectives')
            ->setProject($project)
            ->setDate($date)
            ->setStart($start)
            ->setEnd($end)
        ;
        $this->setReference('meeting1', $meeting);
        $manager->persist($meeting);
        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }
}
