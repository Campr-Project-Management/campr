<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ColorStatus;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for ColorStatus entity.
 */
class LoadColorStatusData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 2; ++$i) {
            $colorStatus = (new ColorStatus())
                ->setName('color-status'.$i)
                ->setSequence($i)
                ->setColor('green')
            ;
            $this->setReference('color-status'.$i, $colorStatus);
            $manager->persist($colorStatus);
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
