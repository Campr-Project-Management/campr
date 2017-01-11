<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Media;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

class LoadMediaData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $fileSystem = $this->getReference('fs');
        $meeting = $this->getReference('meeting1');
        $user = $this->getReference('superadmin');

        $fs = new Filesystem();
        try {
            $fs->mkdir($this->container->getParameter('media_upload_folder_test'));
            $fs->touch($this->container->getParameter('media_upload_folder_test').'/file1.txt');
        } catch (IOExceptionInterface $e) {
            sprintf($e->getMessage());
        }
        $file = new File($this->container->getParameter('media_upload_folder_test').'/file1.txt');
        $fileName = basename($file->getFilename(), '.txt');

        $media = (new Media())
            ->setFileSystem($fileSystem)
            ->setUser($user)
            ->addMeeting($meeting)
            ->setFile($file)
            ->setPath($fileName)
            ->setMimeType($file->getMimeType())
            ->setFileSize($file->getSize())
            ->setCreatedAt(new \DateTime('2017-01-01'))
        ;

        $manager->persist($media);
        $manager->flush();
    }

    /**
     * return int.
     */
    public function getOrder()
    {
        return 6;
    }
}
