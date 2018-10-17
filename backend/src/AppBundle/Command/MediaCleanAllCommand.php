<?php

namespace AppBundle\Command;

use AppBundle\Entity\Team;
use Component\Repository\RepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MediaCleanAllCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:media:clean-all')
            ->setDescription('Schedule deletion of unused media files from all workspaces')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $env = $input->getOption('env');

        $teams = $this->getTeams();
        $found = count($teams);
        if (!$found) {
            $output->writeln('No teams found. Nothing to do. Bye!');

            return 0;
        }

        $command = new MediaCleanCommand();
        foreach ($teams as $team) {
            $output->writeln(sprintf('<info>Processing "%s" ...</info>', $team->getName()));
            $command = sprintf('--env=%s_%s %s', $team->getEnvName(), $env, $command->getName());

            $this->executeEnqueueCommand($command, $output);
        }

        return 0;
    }

    /**
     * @return Team[]
     */
    private function getTeams(): array
    {
        /** @var RepositoryInterface $repo */
        $repo = $this->getContainer()->get('app.repository.team');

        return $repo->findAll();
    }

    /**
     * @param string          $command
     * @param OutputInterface $output
     */
    private function executeEnqueueCommand(string $command, OutputInterface $output)
    {
        $redis = $this->getContainer()->get('redis.client');
        $result = $redis->rpush(
            RedisQueueManagerCommand::DEFAULT,
            [
                $command,
            ]
        );

        if ($result > 0) {
            $output->writeln(sprintf('Command "%s" successfully enqueued.', $command));

            return;
        }

        $output->writeln(sprintf('<error>Command "%s" enqueue FAILED!</error>', $command));
    }
}
