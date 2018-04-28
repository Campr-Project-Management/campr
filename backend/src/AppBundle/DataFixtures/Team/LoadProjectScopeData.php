<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\ProjectScope;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadProjectScopeData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $projectScope = new ProjectScope();
        $projectScope->setName('Default');

        $manager->persist($projectScope);
        $manager->flush();

        $this->setReference('project-scope-default', $projectScope);
    }

    public function getOrder()
    {
        return 1;
    }
}
