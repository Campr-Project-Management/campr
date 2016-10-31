<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Day;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadDayData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $calendar = $this->getReference('calendar1');

        for ($i = 1; $i <= 2; ++$i) {
            $day = (new Day())
                ->setType($i)
                ->setWorking($i * 5)
                ->setCalendar($calendar)
            ;
            $this->setReference('day'.$i, $day);
            $manager->persist($day);
        }

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
