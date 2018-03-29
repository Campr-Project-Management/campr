<?php

namespace AppBundle\Command;

use AppBundle\Entity\Team;
use AppBundle\Repository\WorkPackageRepository;
use Component\Repository\RepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Webmozart\Assert\Assert;

class CleanTutorialsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:workspace:clean:tutorial')
            ->addOption('days', null, InputOption::VALUE_OPTIONAL, 'The days after the tutorial task expired', 5)
            ->addOption('master', null, InputOption::VALUE_NONE, 'All tutorials from all environments will be deleted')
            ->addOption('enqueue', null, InputOption::VALUE_NONE, 'Tutorials deletion command will be enqueued')
            ->setDescription('Deletes all expired tutorials')
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
        $isMaster = $input->getOption('master');
        if ($isMaster) {
            return $this->executeMaster($input, $output);
        }

        return $this->executeSlave($input, $output);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    private function executeSlave(InputInterface $input, OutputInterface $output): int
    {
        $days = (int) $input->getOption('days');
        Assert::greaterThan($days, 0, 'Days value must be greater than 0');

        $before = new \DateTime(sprintf('-%d days', $days));

        $tutorials = $this->getTutorials($before);
        $found = count($tutorials);
        if (!$found) {
            $output->writeln('Nothing to do.');

            return 0;
        }

        $progress = new ProgressBar($output, $found);
        $progress->start();

        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        foreach ($tutorials as $tutorial) {
            $em->remove($tutorial);
            //            $em->flush();

            $progress->advance(1);
        }

        $progress->finish();

        return 0;
    }

    /**
     * @param \DateTime $before
     *
     * @return array
     */
    private function getTutorials(\DateTime $before): array
    {
        return $this
            ->getWorkPackageRepository()
            ->findAllCreatedBefore($before)
        ;
    }

    /**
     * @return WorkPackageRepository
     */
    private function getWorkPackageRepository(): WorkPackageRepository
    {
        return $this->getContainer()->get('app.repository.work_package');
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
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    private function executeMaster(InputInterface $input, OutputInterface $output): int
    {
        $days = (int) $input->getOption('days');
        $isEnqueue = (int) $input->getOption('enqueue');
        Assert::greaterThan($days, 0, 'Days value must be greater than 0');
        $env = $input->getOption('env');

        $teams = $this->getTeams();
        $found = count($teams);
        if (!$found) {
            $output->writeln('No teams found. Nothing to do. Bye!');

            return 0;
        }

        foreach ($teams as $team) {
            $output->writeln(sprintf('<info>Processing "%s" ...</info>', $team->getName()));
            $command = sprintf('--env=%s_%s %s --days=%d', $team->getEnvName(), $env, $this->getName(), $days);

            if ($isEnqueue) {
                $this->executeEnqueueCommand($command, $output);
                continue;
            }

            $this->runCommand($command, $output);
        }

        return 0;
    }

    /**
     * @return string
     */
    private function getCwd(): string
    {
        return $this->getContainer()->getParameter('kernel.root_dir').'/../..';
    }

    /**
     * @param string          $command
     * @param OutputInterface $output
     */
    private function executeEnqueueCommand(string $command, OutputInterface $output)
    {
        $redis = $this->getContainer()->get('redis.client');
        $result = $redis->rpush(
            RedisQueueManagerCommand::TUTORIALS_REMOVAL,
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

    /**
     * @param string          $command
     * @param OutputInterface $output
     */
    private function runCommand(string $command, OutputInterface $output)
    {
        $wd = $this->getCwd();
        $isVerbose = $output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE;

        $process = new Process(
            sprintf('%s bin/console %s', PHP_BINARY, $command),
            $wd,
            null,
            null,
            null
        );

        $callback = null;
        if ($isVerbose) {
            $callback = function ($type, $data) use ($output) {
                $output->write($data);
            };
        }

        $process->run($callback);

        if (0 !== $process->getExitCode()) {
            $output->writeln(
                sprintf(
                    '<error>Executing %s failed</error>',
                    $command
                )
            );
        }

        if ($isVerbose) {
            $output->writeln('');
        }
    }
}
