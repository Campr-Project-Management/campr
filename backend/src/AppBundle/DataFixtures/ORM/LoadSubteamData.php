<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Project;
use AppBundle\Entity\Subteam;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSubteamData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /** @var Project $project */
        $project = $this->getReference('project1');

        $subteam = new Subteam();
        $subteam->setName('Subteam1');
        $subteam->setProject($project);
        $subteam->setDescription('Subteam1 description');

        $this->setReference('subteam-1', $subteam);
        $manager->persist($subteam);

        $subteam = new Subteam();
        $subteam->setName('Subteam2');
        $subteam->setProject($project);
        $subteam->setDescription('Subteam2 description');

        $this->setReference('subteam-2', $subteam);
        $manager->persist($subteam);

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
