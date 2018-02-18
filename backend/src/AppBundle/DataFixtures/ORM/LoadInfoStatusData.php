<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\InfoStatus;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for Note entity.
 */
class LoadInfoStatusData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 2; ++$i) {
            $is = new InfoStatus();
            $is->setName('Info Status '.$i);
            $is->setColor('#000000');
            $this->setReference('infoStatus'.$i, $is);
            $manager->persist($is);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}
