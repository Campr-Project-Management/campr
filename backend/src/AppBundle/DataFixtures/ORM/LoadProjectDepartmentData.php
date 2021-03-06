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
            $projectDepartment = new ProjectDepartment();
            $projectDepartment->setName('project-department'.$i);
            $projectDepartment->setAbbreviation('pd'.$i);
            $projectDepartment->setSequence($i);
            $projectDepartment->setProjectWorkCostType($projectWorkCostType);
            $projectDepartment->setCreatedAt(new \DateTime('2017-01-01 12:00:00'));
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
