<?php

namespace MainBundle\Command;

use AppBundle\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class DeleteTeamCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:remove:deleted-teams')
            ->setDescription('This command will removed soft deleted teams and their databases.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $teamRetentionPeriod = $this->getContainer()->getParameter('app.team.retention_period');
        $repo = $em->getRepository(Team::class);
        $teams = $repo->findDeletedTeams(new \DateInterval($teamRetentionPeriod));

        if (count($teams) === 0) {
            $output->writeln('<info>Nothing to clean up.</info>');
            return;
        }

        foreach ($teams as $team) {
            $output->writeln(sprintf(
                '<info>Removing: %d -> %s</info>',
                $team->getId(),
                $team->getName()
            ));
            if ($this->dropDatabase($team)) {
                $repo->permanentlyRemove($team);
            }
        }
    }

    private function dropDatabase(Team $team)
    {
        $p = new Process(
            sprintf(
                '`which php` bin/console doctrine:database:drop --force --env=%s_%s',
                $team->getSlug(),
                $this->getContainer()
                    ->getParameter('kernel.real_environment')
            ),
            getcwd()
        );

        $p->run();

        // in case you're trying to remove a team where the database has already been removed
        if (strpos($p->getOutput(), 'database doesn\'t exist')) {
            return true;
        }

        return $p->isSuccessful();
    }
}
