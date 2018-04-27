<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\FileSystem as TeamFileSystem;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Refreshes all or one local file-system.
 */
class FileSystemRefreshCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:file-system:refresh')
            ->addOption('name', 'name', InputOption::VALUE_REQUIRED, 'The name of one local file-system')
            ->addOption('all', 'all', InputOption::VALUE_NONE, 'Check all local file-systems')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $rootPath = $this->getContainer()->getParameter('uploads.root_folder');

        $fs = new Filesystem();

        $fileSystems = $input->getOption('all') ? $em->getRepository(TeamFileSystem::class)->findBy(['driver' => TeamFileSystem::LOCAL_ADAPTER]) : ($input->hasOption('name') ? $em->getRepository(TeamFileSystem::class)->findBy(['name' => $input->getOption('name'), 'driver' => TeamFileSystem::LOCAL_ADAPTER]) : []);

        foreach ($fileSystems as $fileSystem) {
            $teamFolder = sprintf('%s/%s', $rootPath, $fileSystem->getName());

            if (!$fs->exists($teamFolder)) {
                $fs->mkdir($teamFolder);
            }

            $fileSystem->setConfig([
                'path' => $teamFolder,
            ]);

            $em->persist($fileSystem);
            $output->writeln(sprintf('<info>File-system %s updated.</info>', $fileSystem->getName()));
        }

        $em->flush();
    }
}
