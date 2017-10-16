<?php

namespace AppBundle\Command\Update;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackage;
use AppBundle\Helper\WorkPackageTreeBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PUIDsCommand extends BaseUpdateCommand
{
    protected function configure()
    {
        $this->setName('app:update:puids');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projects = $this->getEm()->getRepository(Project::class)->findAll();
        if (!$projects || !count($projects)) {
            $output->writeln('<comment>No projects found.</comment>');

            return;
        }
        $this->getEm()->clear();

        // reset all PUIDs before updating
        $this
            ->getEm()
            ->createQuery(sprintf(
                'UPDATE %s AS wp SET wp.puid = wp.id',
                WorkPackage::class
            ))
        ;

        foreach ($projects as $project) {
            $stmt = $this
                ->getEm()
                ->getConnection()
                ->prepare('SELECT * FROM work_package WHERE project_id = :project')
            ;

            $stmt->execute(['project' => $project->getId()]);

            $tree = WorkPackageTreeBuilder::buildFromRawData($stmt->fetchAll());

            array_walk($tree, [$this, 'updateWorkPackages']);
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

        $sql = 'UPDATE work_package SET puid = ?, progress = ? WHERE id = ?';
        $params = [
            $item['puid'],
            $item['progress'],
            $item['id'],
        ];
        $this->getEm()->getConnection()->executeUpdate($sql, $params);

        if (count($item['children'])) {
            array_walk($item['children'], [$this, 'updateWorkPackages']);
        }
    }
}
