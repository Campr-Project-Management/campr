<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\FileSystem;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadFileSystemData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $project = $this->getReference('project1');
        $config = [
            'path' => 'config_path',
        ];

        $fs = (new FileSystem())
            ->setName('fs')
            ->setProject($project)
            ->setDriver('local_driver')
            ->setConfig($config)
        ;
        $this->setReference('fs', $fs);
        $manager->persist($fs);
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
