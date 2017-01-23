<?php

namespace AppBundle\DataFixtures\Team;

use AppBundle\Entity\ProjectStatus;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for ProjectStatus entity.
 */
class LoadProjectStatusData extends AbstractFixture  implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $statusNotStarted = (new ProjectStatus())
            ->setName('Not started')
            ->setSequence(1)
        ;
        $statusInProgress = (new ProjectStatus())
            ->setName('In progress')
            ->setSequence(1)
        ;
        $statusFinished = (new ProjectStatus())
            ->setName('Finished')
            ->setSequence(1)
        ;

        $manager->persist($statusNotStarted);
        $manager->persist($statusInProgress);
        $manager->persist($statusFinished);

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
