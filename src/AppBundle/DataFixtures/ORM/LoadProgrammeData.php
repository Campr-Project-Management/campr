<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Programme;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for Programme entity.
 */
class LoadProgrammeData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 2; ++$i) {
            $programme = new Programme();
            $programme->setName('programme'.$i);
            $this->setReference('programme'.$i, $programme);
            $manager->persist($programme);
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
