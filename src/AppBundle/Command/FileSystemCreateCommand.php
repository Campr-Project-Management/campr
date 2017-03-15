<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use AppBundle\Entity\FileSystem as TeamFileSystem;

/**
 * Create a new file-system object.
 *
 * Command usage: app:file-system:create name
 */
class FileSystemCreateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:file-system:create')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the file-system')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $name = $input->getArgument('name');

        $rootPath = $this->getContainer()->getParameter('uploads.root_folder');
        $teamFolder = sprintf('%s/%s', $rootPath, $name);
        $fs = new Filesystem();
        if (!$fs->exists($teamFolder)) {
            $fs->mkdir($teamFolder);
        }

        $fileSystem = (new TeamFileSystem())
            ->setName($name)
            ->setDriver(TeamFileSystem::LOCAL_ADAPTER)
            ->setConfig(['path' => $teamFolder])
            ->setIsDefault(true)
        ;

        $em->persist($fileSystem);
        $em->flush();

        $output->writeln('<info>File-system created.</info>');
    }
}
