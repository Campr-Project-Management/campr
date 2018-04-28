<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\ProjectCategory;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadProjectCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $projectCategory = new ProjectCategory();
        $projectCategory->setName('Default');

        $manager->persist($projectCategory);
        $manager->flush();

        $this->setReference('project-category-default', $projectCategory);
    }

    public function getOrder()
    {
        return 1;
    }
}
