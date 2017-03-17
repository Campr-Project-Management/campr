<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\WorkPackageCategory;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for WorkPackageCategory entity.
 */
class LoadWorkPackageCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 2; ++$i) {
            $wpCategory = new WorkPackageCategory();
            $wpCategory->setName('wp-category'.$i);
            $this->setReference('wp-category'.$i, $wpCategory);
            $manager->persist($wpCategory);
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
