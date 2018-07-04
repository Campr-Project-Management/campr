<?php

namespace AppBundle\Command\Update;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackage;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PUIDsCommand extends BaseUpdateCommand
{
    protected function configure()
    {
        $this
            ->setName('app:update:puids')
            ->addArgument('project', InputArgument::OPTIONAL, 'Limit the update to 1 item')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->hasArgument('project')) {
            $projects = $this->getEm()->getRepository(Project::class)->findBy([
                'id' => $input->getArgument('project'),
            ]);
        } else {
            $projects = $this->getEm()->getRepository(Project::class)->findAll();
        }

        if (!$projects || !count($projects)) {
            $output->writeln('<comment>No projects found.</comment>');

            return;
        }
        $this->getEm()->clear();

        // reset all PUIDs before updating
        $this
            ->getEm()
            ->createQuery(sprintf(
                'UPDATE %s AS wp SET wp.puid = CONCAT(NOW(), wp.id, RAND())',
                WorkPackage::class
            ))
        ;

        foreach ($projects as $project) {
            $this->processProject($project);
        }
    }

    private function processProject(Project $project)
    {
        $this->output->writeln(sprintf(
            '<info>Processing project #%d - %s</info>',
            $project->getId(),
            $project
        ));

        $data = $this
            ->getContainer()
            ->get('app.service.wbs')
            ->getData($project)
        ;

        $this->calculatePUIDRecursive($data);
    }

    public function calculatePUIDRecursive(array $data)
    {
        $i = 0;
        foreach ($data['children'] as $child) {
            $child['puid'] = ++$i;
            $this->updateWorkPackages($child);
            $this->calculatePUIDRecursive($child);
        }
    }

    private function updateWorkPackages(array $item)
    {
        $this->output->writeln(sprintf(
            '<comment>Setting PUID %s for WP %s (%d)</comment>',
            $item['puid'],
            $item['name'],
            $item['id']
        ));

        $sql = 'UPDATE work_package SET puid = ? WHERE id = ?';
        $params = [
            $item['puid'],
            $item['id'],
        ];
        $this->getEm()->getConnection()->executeUpdate($sql, $params);
    }
}
