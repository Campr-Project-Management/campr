<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ProjectComplexity;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for ProjectComplexity entity.
 */
class LoadProjectComplexityData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 2; ++$i) {
            $projectComplexity = (new ProjectComplexity())
                ->setName('project-complexity'.$i)
                ->setSequence($i)
                ->setCreatedAt(new \DateTime('2017-01-01 12:00:00'))
            ;
            $this->setReference('project-complexity'.$i, $projectComplexity);
            $manager->persist($projectComplexity);
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
