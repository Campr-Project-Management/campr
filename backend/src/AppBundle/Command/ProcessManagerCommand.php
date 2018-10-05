<?php

namespace AppBundle\Command;

use AppBundle\Entity\Team;
use Component\Process\ProcessManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Webmozart\Assert\Assert;

class ProcessManagerCommand extends ContainerAwareCommand
{
    /**
     * The manager should only be run on the main environments!
     * If you want a specific environment, use the --only-env=XXX option.
     */
    const ALLOWED_PROCESS_MANAGERS_ENVS = ['dev', 'qa', 'prod', 'test'];

    public function configure()
    {
        $this
            ->setName('app:process-manager')
            ->addOption('parallel', 'p', InputOption::VALUE_NONE, 'Set whether or not the commands should be ran in parallel.')
            ->addOption('all-envs', null, InputOption::VALUE_NONE, 'Set whether or not the commands should be ran on all available environments.')
            ->addOption('only-env', null, InputOption::VALUE_OPTIONAL, 'Run the command only on this given environment.')
            ->addOption('command', 'c', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Command list that should be ran.')
            ->addOption('timeout', 't', InputOption::VALUE_OPTIONAL, 'Process timeout', 60)
            ->addOption('parallelism', null, InputOption::VALUE_OPTIONAL, 'Max number of parallel processes.', ProcessManager::AUTO_MAX_PROCESS)
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->validateProcessManagerEnvironment($input, $output);

        if ($input->getOption('all-envs')) {
            $environments = $this->getEnvironments($input);
        } elseif ($input->getOption('only-env')) {
            $environments = [$input->getOption('only-env')];
        } else {
            $environments = [];
        }

        $this->printInfo($input, $output, $environments);

        $processes = $this->buildProcesses(
            $input->getOption('command'),
            $environments,
            $input->getOption('timeout')
        );

        $pm = new ProcessManager(
            $input->getOption('parallel'),
            $input->getOption('parallelism'),
            $processes,
            $output
        );

        return $pm->execute();
    }

    /**
     * @param InputInterface $input
     *
     * @return string[]
     */
    private function getEnvironments(InputInterface $input)
    {
        $out = [$input->getOption('env')];

        /** @var Team[] $teams */
        $teams = $this
            ->getContainer()
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository(Team::class)
            ->findAll()
        ;

        foreach ($teams as $team) {
            $out[] = sprintf('%s_%s', $team->getSlug(), $input->getOption('env'));
        }

        return $out;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    private function validateProcessManagerEnvironment(InputInterface $input, OutputInterface $output)
    {
        if (!in_array($input->getOption('env'), self::ALLOWED_PROCESS_MANAGERS_ENVS)) {
            $output->writeln(sprintf(
                'The process manager cannot run on environment "<error>%s</error>", it will only accept one of the following: "<info>%s</info>".',
                $input->getOption('env'),
                implode('</info>", "<info>', self::ALLOWED_PROCESS_MANAGERS_ENVS)
            ));
            exit(-1);
        }

        if ($input->getOption('all-envs') && $input->getOption('only-env')) {
            $output->writeln("You can only use one of the --all-envs or --only-env=XXX options, you can't use both.");
            exit(-1);
        }
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param array           $environments
     */
    private function printInfo(InputInterface $input, OutputInterface $output, array $environments)
    {
        $output->writeln(sprintf(
            '<info>Will now run</info> <comment>%d</comment><info> command(s) <comment>%s</comment>.</info>',
            count($input->getOption('command')),
            $input->getOption('parallel') ? 'in parallel' : 'one at a time'
        ));

        $output->writeln(sprintf(
            '<info>Will run</info> <comment>%s</comment><info>.</info>',
            !$input->getOption('only-env')
                ? 'for environments: '.implode(', ', $environments)
                : 'only for '.$input->getOption('only-env')
        ));
    }

    /**
     * If $environments are specified it will append --env=XXX to each command, otherwise it will simply run the commands
     * allowing us to run linux command and not be limited to Symfony commands.
     *
     * @param array $commands
     * @param array $environments
     * @param $timeout
     *
     * @return array
     */
    private function buildProcesses(array $commands, array $environments = [], $timeout)
    {
        Assert::numeric($timeout);

        $processes = [];

        if ($environments) {
            foreach ($environments as $environment) {
                foreach ($commands as $command) {
                    $processes[] = new Process(
                        $this->stripEnv($command).' --env='.$environment,
                        getcwd(),
                        null,
                        $timeout
                    );
                }
            }
        } else {
            foreach ($commands as $command) {
                $processes[] = new Process(
                    $command,
                    getcwd(),
                    null,
                    $timeout
                );
            }
        }

        return $processes;
    }

    /**
     * @param string $cmd
     *
     * @return string
     */
    private function stripEnv(string $cmd): string
    {
        return preg_replace('/ --env=.+\b/i', '', $cmd);
    }
}
