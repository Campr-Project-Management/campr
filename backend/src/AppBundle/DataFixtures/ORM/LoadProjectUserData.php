<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ProjectUser;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Insert database entries for ProjectUser entity.
 */
class LoadProjectUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = $this->getReference('user3');
        $project = $this->getReference('project1');
        $projectCategory = $this->getReference('project-category1');
        $projectRole = $this->getReference('manager');
        $projectDepartment = $this->getReference('project-department1');
        $projectTeam = $this->getReference('project-team1');

        $projectUser1 = new ProjectUser();
        $projectUser1->setUser($user);
        $projectUser1->setProject($project);
        $projectUser1->setProjectCategory($projectCategory);
        $projectUser1->addProjectRole($projectRole);
        $projectUser1->addProjectDepartment($projectDepartment);
        $projectUser1->setProjectTeam($projectTeam);
        $projectUser1->setCreatedAt(new \DateTime('2017-01-01 12:00:00'));
        $manager->persist($projectUser1);

        $user = $this->getReference('user4');
        $project = $this->getReference('project1');
        $projectCategory = $this->getReference('project-category2');
        $projectRole = $this->getReference('sponsor');
        $projectDepartment = $this->getReference('project-department2');
        $projectTeam = $this->getReference('project-team2');

        $projectUser2 = new ProjectUser();
        $projectUser2->setUser($user);
        $projectUser2->setProject($project);
        $projectUser2->setProjectCategory($projectCategory);
        $projectUser2->addProjectRole($projectRole);
        $projectUser2->addProjectDepartment($projectDepartment);
        $projectUser2->setProjectTeam($projectTeam);
        $projectUser2->setCreatedAt(new \DateTime('2017-01-01 12:00:00'));
        $manager->persist($projectUser2);

        $user = $this->getReference('user5');
        $project = $this->getReference('project1');
        $projectCategory = $this->getReference('project-category1');
        $projectRole = $this->getReference('team-member');
        $projectDepartment = $this->getReference('project-department1');
        $projectTeam = $this->getReference('project-team1');

        $projectUser3 = new ProjectUser();
        $projectUser3->setUser($user);
        $projectUser3->setProject($project);
        $projectUser3->setProjectCategory($projectCategory);
        $projectUser3->addProjectRole($projectRole);
        $projectUser3->addProjectDepartment($projectDepartment);
        $projectUser3->setProjectTeam($projectTeam);
        $projectUser3->setCreatedAt(new \DateTime('2017-01-01 12:00:00'));
        $manager->persist($projectUser3);

        $user = $this->getReference('user6');
        $project = $this->getReference('project2');
        $projectCategory = $this->getReference('project-category2');
        $projectRole = $this->getReference('team-participant');
        $projectDepartment = $this->getReference('project-department2');
        $projectTeam = $this->getReference('project-team2');

        $projectUser4 = new ProjectUser();
        $projectUser4->setUser($user);
        $projectUser4->setProject($project);
        $projectUser4->setProjectCategory($projectCategory);
        $projectUser4->addProjectRole($projectRole);
        $projectUser4->addProjectDepartment($projectDepartment);
        $projectUser4->setProjectTeam($projectTeam);
        $projectUser4->setCreatedAt(new \DateTime('2017-01-01 12:00:00'));
        $manager->persist($projectUser4);

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 5;
    }
}
