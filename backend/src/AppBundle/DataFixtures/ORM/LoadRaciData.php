<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Raci;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for Raci entity.
 */
class LoadRaciData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = $this->getReference('user4');
        $workPackage = $this->getReference('work-package1');

        for ($i = 1; $i <= 2; ++$i) {
            $raci = (new Raci())
                ->setData('data'.$i)
                ->setUser($user)
                ->setWorkPackage($workPackage)
            ;

            $manager->persist($raci);
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
