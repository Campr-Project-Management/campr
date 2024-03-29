<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ProjectModule;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for ProjectModule entity.
 */
class LoadProjectModuleData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $project = $this->getReference('project1');
        for ($i = 1; $i <= 2; ++$i) {
            $projectModule = new ProjectModule();
            $projectModule->setModule('project-module'.$i);
            $projectModule->setIsEnabled(true);
            $projectModule->setProject($project);
            $projectModule->setCreatedAt(new \DateTime('2017-01-01 12:00:00'));

            $manager->persist($projectModule);
        }

        $projectModule = new ProjectModule();
        $projectModule->setModule('project-module'.$i);
        $projectModule->setProject($project);
        $projectModule->setIsEnabled(true);
        $projectModule->setIsRequired(true);
        $projectModule->setCreatedAt(new \DateTime('2017-01-01 12:00:00'));
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
