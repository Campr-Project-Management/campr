<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\FileSystem;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadFileSystemData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $project = $this->getReference('project1');
        $config = [
            'path' => $this->container->getParameter('media_upload_folder_test'),
        ];

        $fs = (new FileSystem())
            ->setName('fs')
            ->setProject($project)
            ->setDriver(FileSystem::LOCAL_ADAPTER)
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
