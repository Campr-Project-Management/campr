<?php

namespace AppBundle\Command\Update;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackage;
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
                'UPDATE %s AS wp SET wp.puid = CONCAT(wp.id, :rand)',
                WorkPackage::class
            ))
            ->execute([
                'rand' => random_int(0, time()),
            ])
        ;
        foreach ($projects as $project) {
            $output->writeln(sprintf(
                '<info>Processing: %s</info>',
                $project
            ));
            $this->updateWorkPackages($project);
        }
    }

    private function printWps(array $workPackages, $level = 0)
    {
        foreach ($workPackages as $wp) {
            echo str_repeat('  ', $level), "ID: {$wp['id']} / Type: {$wp['type']} / Puid: {$wp['puid']}, Name: {$wp['name']}", PHP_EOL;
            $this->printWps($wp['children'], $level + 1);
        }
    }

    private function updateWorkPackages(Project $project, string $puidPrefix = '', array $workPackageParent = null)
    {
        static $done = [];
        $wps = $this->getWorkPackages($project, $workPackageParent);
        $c = 0;
        foreach ($wps as $wp) {
            if (in_array($wp['id'], $done)) {
                continue;
            }
            $done[] = $wp['id'];
            ++$c;
            $sql = 'UPDATE work_package wp SET wp.puid = ? WHERE wp.id = ?';
            $params = [
                $puidPrefix ? $puidPrefix.'.'.$c : $c,
                $wp['id'],
            ];
            $this->getEm()->getConnection()->executeUpdate($sql, $params);
            $this->output->writeln(sprintf(
                '<comment>Setting puid %s for WP %s (%d)</comment>',
                $params[0],
                $wp['name'],
                $wp['id']
            ));
            $this->updateWorkPackages($project, $params[0], $wp);
        }
    }
}
