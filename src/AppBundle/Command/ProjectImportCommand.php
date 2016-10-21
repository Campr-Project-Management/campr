<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class ProjectImportCommand extends ContainerAwareCommand
{
    private $files;

    private $importService;

    protected function configure()
    {
        $this
            ->setName('app:project-import')
            ->addArgument('xml-file', InputArgument::REQUIRED, 'XML file is required')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $path = $this->getContainer()->getParameter('import_folder');
        $finder = new Finder();
        $name = $input->getArgument('xml-file');

        $this->files = $finder->files()->in($path)->name($name);
        $this->importService = $this->getContainer()->get('app.service.import');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Import started.</info>');

        foreach ($this->files as $file) {
            $content = file_get_contents($file->getRealpath());
            $this->importService->importProjects($content);
        }

        $output->writeln('<info>Import finished.</info>');
    }
}
