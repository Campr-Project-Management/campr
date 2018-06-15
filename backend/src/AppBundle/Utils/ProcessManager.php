<?php

namespace AppBundle\Utils;

use Symfony\Component\Process\Process;

/**
 * This ProcessManager is a simple wrapper to enable parallel processing using Symfony Process component.
 */
class ProcessManager
{
    /**
     * @param array $processes
     * @param $maxParallel
     * @param int $poll
     *
     * @return string
     */
    public static function runParallel(array $processes, $maxParallel, $poll = 1000)
    {
        self::validateProcesses($processes);

        $output = null;

        // do not modify the object pointers in the argument, copy to local working variable
        $processesQueue = $processes;

        // fix maxParallel to be max the number of processes or positive
        $maxParallel = min(abs($maxParallel), count($processesQueue));

        // get the first stack of processes to start at the same time
        /** @var Process[] $currentProcesses */
        $currentProcesses = array_splice($processesQueue, 0, $maxParallel);

        // start the initial stack of processes
        foreach ($currentProcesses as $process) {
            $process->start();
        }

        do {
            // wait for the given time
            usleep($poll);

            // remove all finished processes from the stack
            foreach ($currentProcesses as $index => $process) {
                if (!$process->isRunning()) {
                    $output[] = $process->getOutput();

                    unset($currentProcesses[$index]);

                    // directly add and start new process after the previous finished
                    if (count($processesQueue) > 0) {
                        $nextProcess = array_shift($processesQueue);
                        $nextProcess->start();
                        $currentProcesses[] = $nextProcess;
                    }
                }
            }
            // continue loop while there are processes being executed or waiting for execution
        } while (count($processesQueue) > 0 || count($currentProcesses) > 0);

        return implode('', $output);
    }

    /**
     * @param Process[] $processes
     */
    private static function validateProcesses(array $processes)
    {
        if (empty($processes)) {
            throw new \InvalidArgumentException('Can not run in parallel 0 commands');
        }

        foreach ($processes as $process) {
            if (!($process instanceof Process)) {
                throw new \InvalidArgumentException('Process in array need to be instance of Symfony Process');
            }
        }
    }
}
