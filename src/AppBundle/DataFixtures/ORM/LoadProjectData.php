<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Project;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadProjectData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $sponsorUser = $this->getReference('user2');
        $managerUser = $this->getReference('user3');
        $projectComplexity = $this->getReference('project-complexity1');
        $projectCategory = $this->getReference('project-category1');
        $projectScope = $this->getReference('project-scope1');
        $projectStatus = $this->getReference('project-status1');
        $portfolio = $this->getReference('portfolio1');

        $project1 = (new Project())
            ->setName('project1')
            ->setNumber('project-number-1')
            ->setSponsor($sponsorUser)
            ->setManager($managerUser)
            ->setProjectComplexity($projectComplexity)
            ->setProjectCategory($projectCategory)
            ->setProjectScope($projectScope)
            ->setStatus($projectStatus)
            ->setPortfolio($portfolio)
        ;
        $manager->persist($project1);
        $this->setReference('project1', $project1);

        $projectComplexity = $this->getReference('project-complexity2');
        $projectCategory = $this->getReference('project-category2');
        $projectScope = $this->getReference('project-scope2');
        $projectStatus = $this->getReference('project-status2');
        $portfolio = $this->getReference('portfolio2');

        $project2 = (new Project())
            ->setName('project2')
            ->setNumber('project-number-2')
            ->setSponsor($sponsorUser)
            ->setManager($managerUser)
            ->setProjectComplexity($projectComplexity)
            ->setProjectCategory($projectCategory)
            ->setProjectScope($projectScope)
            ->setStatus($projectStatus)
            ->setPortfolio($portfolio)
        ;
        $manager->persist($project2);
        $this->setReference('project2', $project2);

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}
