<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Timephase;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for Timephase entity.
 */
class LoadTimephaseData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $startedAt = new \Datetime('2016-12-12 11:30:00');
        $finishedAt = new \Datetime('2016-12-12 13:00:00');

        for ($i = 1; $i <= 2; ++$i) {
            $assignment = $this->getReference('assignment'.$i);
            $timephase = (new Timephase())
                ->setType($i)
                ->setUnit($i * $i)
                ->setValue('value'.$i)
                ->setStartedAt($startedAt)
                ->setFinishedAt($finishedAt)
                ->setAssignment($assignment)
            ;

            $manager->persist($timephase);
        }

        $timephase = (new timephase())
            ->setType(1)
            ->setUnit(2)
            ->setValue('value')
            ->setStartedAt(new \Datetime('2017-01-01 12:00:00'))
            ->setFinishedAt(new \Datetime('2017-01-01 15:00:00'))
            ->setAssignment($this->getreference('assignment3'))
        ;
        $manager->persist($timephase);

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
