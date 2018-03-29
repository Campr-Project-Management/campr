<?php

namespace AppBundle\Command;

use AppBundle\Entity\CommandQueueLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class RedisQueueManagerCommand extends ContainerAwareCommand
{
    const DEFAULT = 'default';
    const IMPORT = 'import';
    const AUTOMAILER = 'automailer';
    const TUTORIALS_REMOVAL = 'tutorials_removal';

    const TTL = 60;
    const JOB_DELAY = 5;
    const SLEEP_BEFORE_EXIT = 5;
    const MEMORY_PERCENT_FREE_REQUIRED = 8; // MACHINE
    const MEMORY_USAGE_LIMIT = 800; // PHP (MB)
    const MAX_REQUEUE_COUNT = 9;

    private $startTime;
    private $list;
    private $ttl;
    private $jobDelay;
    private $sleepBeforeExit;
    private $maxRequeueCount;

    protected function configure()
    {
        $this
            ->setName('app:redis:queue:manager')
            ->addArgument('list', InputArgument::REQUIRED, 'List name', null)
            ->addOption('ttl', null, InputOption::VALUE_OPTIONAL, 'Time to live', self::TTL)
            ->addOption('job-delay', null, InputOption::VALUE_OPTIONAL, 'Job delay', self::JOB_DELAY)
            ->addOption('sleep-before-exit', null, InputOption::VALUE_OPTIONAL, 'Job delay', self::SLEEP_BEFORE_EXIT)
            ->addOption('max-requeue-count', null, InputOption::VALUE_OPTIONAL, 'Max Requeue Count', self::MAX_REQUEUE_COUNT)
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->startTime = time();
        $output->writeln(sprintf(
            '<info>Started Redis Queue Manager at: %s</info>',
            date('Y-m-d H:i:s', $this->startTime)
        ));

        $this->list = $input->getArgument('list');

        $this->ttl = $input->getOption('ttl');
        $this->jobDelay = $input->getOption('job-delay');
        $this->sleepBeforeExit = $input->getOption('sleep-before-exit');
        $this->maxRequeueCount = $input->getOption('max-requeue-count');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \Predis\Client $redis */
        $redis = $this->getContainer()->get('redis.client');
        $wd = $this->getContainer()->getParameter('kernel.root_dir').'/../..';

        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        while ($message = $redis->blpop($this->list, $this->ttl - (time() - $this->startTime))) {
            array_shift($message);
            $message = implode(' ', $message);
            if ($this->ttl && $this->getRunningTime() >= $this->ttl) {
                $redis->rpush($this->list, [$message]);
                $output->writeln(sprintf(
                    "<comment>I've been running for %s second(s). TTL of %s reached or surpassed. Current job was added at the end of the queue.</comment>",
                    $this->getRunningTime(),
                    $this->ttl
                ));
                break;
            }

            if ($this->jobDelay > 0) {
                $output->writeln(sprintf(
                    '<info>Applying job delay of %d second(s).</info>',
                    $this->jobDelay
                ));
                sleep($this->jobDelay);
                $output->writeln('<info>Done applying delay.</info>');
            }

            $match = preg_match('/ --queue-count=([0-9]+)/', $message, $matches);
            $count = isset($matches[1]) ? intval($matches[1]) : 0;

            if ($count === $this->maxRequeueCount) {
                continue;
            }

            if ($match) {
                $message = preg_replace('/ --queue-count=([0-9]+)/', '', $message);
            }

            $commandQueueLog = new CommandQueueLog();
            $commandQueueLog->setCommand($message);
            $commandQueueLog->setQueueCount($count);

            $process = new Process(
                sprintf('%s bin/console %s', PHP_BINARY, $message),
                $wd,
                null,
                null,
                null
            );
            $process->run();

            $commandQueueLog->setExitCode($process->getExitCode());
            $commandQueueLog->setOutput($process->getOutput());

            if ($process->getExitCode() !== 0 && $count < $this->maxRequeueCount) {
                $redis->rpush($this->list, [$message.' --queue-count='.($count + 1)]);
                $output->writeln(sprintf(
                    '<error>Executing %s failed. Requeueing.</error>',
                    $message
                ));
            }
            $em->persist($commandQueueLog);
            $em->flush();

            $em->clear();

            $output->writeln('<info>Done: </info> '.$message);
        }

        if ($this->sleepBeforeExit > 0) {
            $output->writeln(sprintf('<comment>I will now take a %d second(s) nap.</comment>', $this->sleepBeforeExit));
            sleep($this->sleepBeforeExit);
            $output->writeln('<info>Done napping.</info>');
        }

        $output->writeln('<info>Bye!</info>');
    }

    /**
     * @return int
     */
    private function getRunningTime()
    {
        return time() - $this->startTime;
    }
}
