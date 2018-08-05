<?php

namespace Component\Process;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Webmozart\Assert\Assert;

class ProcessManager
{
    const AUTO_MAX_PROCESS = -1;

    /**
     * @var bool
     */
    private $parallel;

    /**
     * @var int
     */
    private $maxProcesses;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var Process[]
     */
    private $processPool;

    /**
     * ProcessManager constructor.
     * @param bool $parallel
     * @param int $maxProcesses
     * @param array $processes
     * @param OutputInterface $output
     */
    public function __construct(
        bool $parallel = true,
        int $maxProcesses = self::AUTO_MAX_PROCESS,
        array $processes = [],
        OutputInterface $output
    ) {
        $this->parallel = $parallel;

        if ($maxProcesses === self::AUTO_MAX_PROCESS) {
            $maxProcesses = $this->getNumberOfCPUs();
        }

        if ($maxProcesses < 1) {
            throw new \LogicException("You can't have less than 1 thread.");
        }

        if ($processes) {
            Assert::allIsInstanceOf($processes, Process::class);
        }

        $this->processPool = $processes;
        $this->maxProcesses = $maxProcesses;
        $this->output = $output;
    }

    /**
     * @return int
     */
    private function getNumberOfCPUs(): int
    {
        return (int) `cat /proc/cpuinfo | grep processor | wc -l`;
    }

    /**
     * @param Process $process
     */
    public function addProcess(Process $process)
    {
        if ($process->isTerminated()) {
            throw new \LogicException("You can't add a process that is already terminated.");
        }

        $this->processPool[] = $process;
    }

    /**
     * @param array $processes
     */
    public function addProcesses(array $processes)
    {
        foreach ($processes as $process) {
            $this->addProcess($process);
        }
    }

    public function getRunningProcessesCount()
    {
        $out = 0;

        foreach ($this->processPool as $process) {
            $out += ($process->isRunning() ? 1 : 0);
        }

        return $out;
    }

    /**
     * @return int
     */
    public function execute(): int
    {
        if ($this->parallel) {
            return $this->executeInParallel();
        } else {
            return $this->executeInSeries();
        }
    }

    /**
     * @return int
     */
    private function executeInParallel(): int
    {
        $done = [];

        while (count($done) !== count($this->processPool)) {
            foreach ($this->processPool as $process) {
                if (!$process->isStarted() && $this->getRunningProcessesCount() < $this->maxProcesses) {
                    $process->start();
                }

                if ($process->isTerminated() && $this->output && !in_array(spl_object_hash($process), $done)) {
                    $done[] = spl_object_hash($process);
                    $this->output->writeln(sprintf(
                        '[%s] %s',
                        $process->getCommandLine(),
                        $process->getOutput()
                    ));
                }
            }
        }

        return count($done) - count($this->processPool);
    }

    /**
     * @return int
     */
    private function executeInSeries(): int
    {
        foreach ($this->processPool as $process) {
            $process->run();
            if ($this->output) {
                $this->output->writeln(sprintf(
                    '[%s] %s',
                    $process->getCommandLine(),
                    $process->getOutput()
                ));
            }
        }

        return 0;
    }
}
