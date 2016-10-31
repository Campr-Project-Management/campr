<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ProjectUser;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadProjectUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = $this->getReference('user4');
        $project = $this->getReference('project1');
        $projectCategory = $this->getReference('project-category1');
        $projectRole = $this->getReference('team-member');
        $projectDepartment = $this->getReference('project-department1');
        $projectTeam = $this->getReference('project-team1');

        $projectUser1 = (new ProjectUser())
            ->setUser($user)
            ->setProject($project)
            ->setProjectCategory($projectCategory)
            ->setProjectRole($projectRole)
            ->setProjectDepartment($projectDepartment)
            ->setProjectTeam($projectTeam)
        ;
        $manager->persist($projectUser1);

        $user = $this->getReference('user5');
        $project = $this->getReference('project2');
        $projectCategory = $this->getReference('project-category2');
        $projectRole = $this->getReference('team-participant');
        $projectDepartment = $this->getReference('project-department2');
        $projectTeam = $this->getReference('project-team2');

        $projectUser2 = (new ProjectUser())
            ->setUser($user)
            ->setProject($project)
            ->setProjectCategory($projectCategory)
            ->setProjectRole($projectRole)
            ->setProjectDepartment($projectDepartment)
            ->setProjectTeam($projectTeam)
        ;
        $manager->persist($projectUser2);

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }
}
