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
        $startedAt = new \DateTime();

        for ($i = 1; $i <= 2; ++$i) {
            $assignment = $this->getReference('assignment'.$i);
            $finishedAt = new \DateTime(sprintf('+%d days', $i + 1));

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
