<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\WorkingTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for WorkingTime entity.
 */
class LoadWorkingTimeData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $day = $this->getReference('day1');
        $fromTime = new \DateTime('2017-01-01 14:30:00');
        $toTime = new \DateTime('2017-01-01 18:30:00');

        for ($i = 1; $i <= 2; ++$i) {
            $workingTime = (new WorkingTime())
                ->setDay($day)
                ->setFromTime($fromTime)
                ->setToTime($toTime)
            ;

            $manager->persist($workingTime);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 5;
    }
}
