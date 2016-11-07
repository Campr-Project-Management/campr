<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ProjectModule;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadProjectModuleData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 2; ++$i) {
            $projectModule = (new ProjectModule())
                ->setModule('project-module'.$i)
                ->setIsEnabled(true)
            ;
            $manager->persist($projectModule);
        }

        $project = $this->getReference('project1');
        $projectModule = (new ProjectModule())
            ->setModule('project-module'.$i)
            ->setProject($project)
            ->setIsEnabled(true)
            ->setIsRequired(true)
        ;
        $manager->persist($projectModule);

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
