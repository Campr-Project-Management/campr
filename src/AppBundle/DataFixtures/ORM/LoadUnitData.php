<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Unit;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for Unit entity.
 */
class LoadUnitData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $project1 = $this->getReference('project1');

        for ($i = 1; $i <= 2; ++$i) {
            $unit = (new Unit())
                ->setName('unit'.$i)
                ->setSequence($i)
                ->setProject($project1)
            ;
            $manager->persist($unit);
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
