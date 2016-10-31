<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\WorkingTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadWorkingTimeData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $day = $this->getReference('day1');

        for ($i = 1; $i <= 2; ++$i) {
            $fromTime = new \DateTime(sprintf('+%d hours', $i + 1));
            $toTime = new \DateTime(sprintf('+%d hours', $i + 2));

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
