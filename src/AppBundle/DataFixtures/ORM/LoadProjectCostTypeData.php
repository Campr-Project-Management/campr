<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ProjectCostType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for ProjectCostType entity.
 */
class LoadProjectCostTypeData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 2; ++$i) {
            $projectCostType = (new ProjectCostType())
                ->setName('project-cost-type'.$i)
                ->setSequence($i)
                ->setCreatedAt(new \DateTime('2017-01-01 12:00:00'))
            ;
            $this->setReference('project-cost-type'.$i, $projectCostType);
            $manager->persist($projectCostType);
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
