<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Calendar;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCalendarData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $project = $this->getReference('project1');

        for ($i = 1; $i <= 2; ++$i) {
            $calendar = (new Calendar())
                ->setName('calendar'.$i)
                ->setProject($project)
                ->setIsBaseline(true)
            ;
            $this->setReference('calendar'.$i, $calendar);
            $manager->persist($calendar);
        }

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
