<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectTeam;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for ProjectTeam entity.
 */
class LoadProjectTeamData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /** @var Project $project */
        $project = $this->getReference('project1');

        for ($i = 1; $i <= 2; ++$i) {
            $projectTeam = new ProjectTeam();
            $projectTeam->setName('project-team'.$i);
            $projectTeam->setProject($project);
            $projectTeam->setCreatedAt(new \DateTime('2017-01-01 12:00:00'));
            $this->setReference('project-team'.$i, $projectTeam);
            $manager->persist($projectTeam);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 4;
    }
}
