<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ProjectStatus;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadProjectStatusData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 2; ++$i) {
            $projectStatus = (new ProjectStatus())
                ->setName('project-status'.$i)
                ->setSequence($i)
            ;
            $this->setReference('project-status'.$i, $projectStatus);
            $manager->persist($projectStatus);
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
