<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="command_queue_log")
 */
class CommandQueueLog
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\Column(name="id", type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="command", type="text")
     */
    private $command;

    /**
     * @var string
     *
     * @ORM\Column(name="queue_count", type="integer", options={"default":0})
     */
    private $queueCount;

    /**
     * @var string
     *
     * @ORM\Column(name="output", type="text", nullable=true)
     */
    private $output;

    /**
     * @var string
     *
     * @ORM\Column(name="exit_code", type="integer")
     */
    private $exitCode;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set command.
     *
     * @param string $command
     *
     * @return CommandQueueLog
     */
    public function setCommand($command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Get command.
     *
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Set queueCount.
     *
     * @param int $queueCount
     *
     * @return CommandQueueLog
     */
    public function setQueueCount($queueCount)
    {
        $this->queueCount = $queueCount;

        return $this;
    }

    /**
     * Get queueCount.
     *
     * @return int
     */
    public function getQueueCount()
    {
        return $this->queueCount;
    }

    /**
     * Set output.
     *
     * @param string $output
     *
     * @return CommandQueueLog
     */
    public function setOutput($output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * Get output.
     *
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Set exitCode.
     *
     * @param int $exitCode
     *
     * @return CommandQueueLog
     */
    public function setExitCode($exitCode)
    {
        $this->exitCode = $exitCode;

        return $this;
    }

    /**
     * Get exitCode.
     *
     * @return int
     */
    public function getExitCode()
    {
        return $this->exitCode;
    }
}
