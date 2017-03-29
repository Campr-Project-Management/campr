<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ProjectScope;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for ProjectScope entity.
 */
class LoadProjectScopeData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 2; ++$i) {
            $projectScope = (new ProjectScope())
                ->setName('project-scope'.$i)
                ->setSequence($i)
                ->setCreatedAt(new \DateTime('2017-01-01 12:00:00'))
            ;
            $this->setReference('project-scope'.$i, $projectScope);
            $manager->persist($projectScope);
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
