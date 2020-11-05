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
            [ProjectStatus::CODE_NOT_STARTED, 0],
            [ProjectStatus::CODE_IN_PROGRESS, 1],
            [ProjectStatus::CODE_PENDING, 2],
            [ProjectStatus::CODE_OPEN, 3],
            [ProjectStatus::CODE_CLOSED, 4],
        ];

        foreach ($data as $row) {
            $ps = $manager
                ->getRepository(ProjectStatus::class)
                ->findOneBy([
                    'name' => $row[0],
                ])
            ;
            if (!$ps) {
                $ps = new ProjectStatus();
            }
            $ps->setName($row[0]);
            $ps->setCode($row[0]);
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
