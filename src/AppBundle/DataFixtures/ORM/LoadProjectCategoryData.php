<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ProjectCategory;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for ProjectCategory entity.
 */
class LoadProjectCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 2; ++$i) {
            $projectCategory = (new ProjectCategory())
                ->setName('project-category'.$i)
                ->setSequence($i)
            ;
            $this->setReference('project-category'.$i, $projectCategory);
            $manager->persist($projectCategory);
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
