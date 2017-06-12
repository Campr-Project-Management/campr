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
        $this->getEm()->beginTransaction();
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
        $this->getEm()->commit();
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
        $wps = $this->getWorkPackages($project, $workPackageParent, false, true);
        $c = 0;
        foreach ($wps as $wp) {
            ++$c;
            $dql = sprintf(
                'UPDATE %s wp SET wp.puid = :puid WHERE wp.id = :id',
                WorkPackage::class
            );
            $params = [
                'puid' => $puidPrefix ? $puidPrefix.'.'.$c : $c,
                'id' => $wp['id'],
            ];
            $this->output->writeln(sprintf(
                '<comment>Setting puid %s for WP %s</comment>',
                $params['puid'],
                $wp['name']
            ));
            $this
                ->getEm()
                ->createQuery($dql)
                ->execute($params)
            ;
            $this->updateWorkPackages($project, $params['puid'], $wp);
        }
    }
}
