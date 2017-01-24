<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * Migrates all databases.
 *
 * Command usage: app:migrate:all-databases
 */
class MigrateAllDatabasesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:migrate:all-databases')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $wd = $this->getContainer()->getParameter('kernel.root_dir').'/..';
        $env = $this->getContainer()->get('kernel')->getEnvironment();
        $em = $this->getContainer()->get('doctrine')->getEntityManager();

        /* Migrate main database */
        $command = sprintf(
            '--env=%s doctrine:migrations:migrate -n',
            $env
        );
        $process = new Process(
            sprintf('%s app/console %s', PHP_BINARY, $command),
            $wd,
            null,
            null,
            null
        );
        $process->run();

        if ($process->getExitCode() !== 0) {
            $output->writeln(sprintf(
                '<error>Executing %s failed.</error>',
                $command
            ));
        }

        /* Migrate team databases */
        $sql = 'SELECT slug from team';
        $stmt = $em
            ->getConnection()
            ->prepare($sql)
        ;
        $stmt->execute();
        $teamSlugs = $stmt->fetchAll();

        foreach ($teamSlugs as $teamSlug) {
            $command = sprintf(
                '--env=%s_%s doctrine:migrations:migrate -n',
                str_replace('-', '_', $teamSlug['slug']),
                $env
            );
            $process = new Process(
                sprintf('%s app/console %s', PHP_BINARY, $command),
                $wd,
                null,
                null,
                null
            );
            $process->run();

            if ($process->getExitCode() !== 0) {
                $output->writeln(sprintf(
                    '<error>Executing %s failed.</error>',
                    $command
                ));
            }
        }
    }
}
