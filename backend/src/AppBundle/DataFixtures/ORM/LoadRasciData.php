<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Rasci;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for Rasci entity.
 */
class LoadRasciData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = $this->getReference('user4');
        $workPackage = $this->getReference('work-package1');

        for ($i = 1; $i <= 2; ++$i) {
            $rasci = (new Rasci())
                ->setData('data'.$i)
                ->setUser($user)
                ->setWorkPackage($workPackage)
            ;

            $manager->persist($rasci);
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
