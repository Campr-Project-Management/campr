<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Impact;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for Impact entity.
 */
class LoadImpactData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 2; ++$i) {
            $impact = (new Impact())
                ->setName('impact'.$i)
                ->setSequence($i)
            ;
            $this->setReference('impact'.$i, $impact);
            $manager->persist($impact);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}
