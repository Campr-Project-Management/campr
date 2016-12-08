<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ProjectDepartment;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for ProjectDepartment entity.
 */
class LoadProjectDepartmentData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 2; ++$i) {
            $projectWorkCostType = $this->getReference('project-work-cost-type'.$i);
            $projectDepartment = (new ProjectDepartment())
                ->setName('project-department'.$i)
                ->setAbbreviation('pd'.$i)
                ->setSequence($i)
                ->setProjectWorkCostType($projectWorkCostType)
            ;
            $this->setReference('project-department'.$i, $projectDepartment);
            $manager->persist($projectDepartment);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}
