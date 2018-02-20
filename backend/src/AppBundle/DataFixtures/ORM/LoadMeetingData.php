<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Meeting;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for Meeting entity.
 */
class LoadMeetingData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $project = $this->getReference('project1');
        $date = new \DateTime('2017-01-01');
        $start = new \DateTime('2017-01-01 07:00:00');
        $end = new \DateTime('2017-01-01 12:00:00');

        $meeting = (new Meeting())
            ->setCreatedBy($this->getReference('superadmin'))
            ->setName('meeting1')
            ->setLocation('location1')
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
