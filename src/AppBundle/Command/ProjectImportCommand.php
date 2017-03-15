<?php

namespace AppBundle\Command;

use AppBundle\Entity\FileSystem;
use AppBundle\Entity\Media;
use AppBundle\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

/**
 * Import projects from xml command.
 *
 * Command usage: app:project-import MediaEntityId
 */
class ProjectImportCommand extends ContainerAwareCommand
{
    private $files;

    private $importService;

    private $em;

    /** @var Media $media */
    private $media;

    protected function configure()
    {
        $this
            ->setName('app:project-import')
            ->addArgument('media-id', InputArgument::REQUIRED, 'Media Entity id is required')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get('doctrine')->getManager();
        $finder = new Finder();
        $id = $input->getArgument('media-id');

        $this->media = $this
            ->em
            ->getRepository(Media::class)
            ->find($id)
        ;
        $fileSystem = $this->media->getFileSystem();
        $path = $fileSystem->getConfig()['path'];

        $this->files = $finder->files()->in($path)->name($this->media->getPath());
        $this->importService = $this->getContainer()->get('app.service.import');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $project = new Project();
        $output->writeln('<info>Import started.</info>');

        foreach ($this->files as $file) {
            $content = file_get_contents($file->getRealpath());
            $this->importService->importProjects($project, $content);
        }
        $output->writeln('<info>Import finished.</info>');

        $this->em->flush();
    }
}
