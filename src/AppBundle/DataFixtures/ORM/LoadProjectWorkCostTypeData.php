<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ProjectWorkCostType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for ProjectWorkCostType entity.
 */
class LoadProjectWorkCostTypeData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 2; ++$i) {
            $projectWorkCostType = (new ProjectWorkCostType())
                ->setName('project-work-cost-type'.$i)
                ->setSequence($i)
                ->setCreatedAt(new \DateTime('2017-01-01 12:00:00'))
            ;
            $this->setReference('project-work-cost-type'.$i, $projectWorkCostType);
            $manager->persist($projectWorkCostType);
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
