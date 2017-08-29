<?php

namespace AppBundle\DataFixtures\Team;

use AppBundle\Entity\ProjectStatus;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for ProjectStatus entity.
 */
class LoadProjectStatusData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $data = [
            ['label.not_started', -1],
            ['label.in_progress', 0],
            ['label.pending', 1],
            ['label.open', 2],
            ['label.closed', -1],
        ];

        foreach ($data as $row) {
            $ps = new ProjectStatus();
            $ps->setName($row[0]);
            $ps->setSequence($row[1]);
            $manager->persist($ps);
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
