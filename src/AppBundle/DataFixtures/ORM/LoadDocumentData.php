<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Document;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

class LoadDocumentData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $meeting = $this->getReference('meeting1');

        $fs = new Filesystem();
        try {
            $fs->mkdir($this->container->getParameter('documents_upload_folder'));
            $fs->touch($this->container->getParameter('documents_upload_folder').'/file1.txt');
        } catch (IOExceptionInterface $e) {
            sprintf($e->getMessage());
        }
        $file = new File($this->container->getParameter('documents_upload_folder').'/file1.txt');
        $fileName = basename($file->getFilename(), '.txt');

        $document = (new Document())
            ->setProject($project)
            ->addMeeting($meeting)
            ->setFile($file)
            ->setFileName($fileName)
        ;

        $manager->persist($document);
        $manager->flush();
    }

    /**
     * return int.
     */
    public function getOrder()
    {
        return 4;
    }
}
