<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\InfoCategory;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for Note entity.
 */
class LoadInfoCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 2; ++$i) {
            $ic = new InfoCategory();
            $ic->setName('Info Category '.$i);
            $this->setReference('infoCategory'.$i, $ic);
            $manager->persist($ic);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}
