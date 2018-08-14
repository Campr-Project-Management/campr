<?php

namespace Component\Project\Calculator;

class WorkPackageStatusTotalCount
{
    /**
     * @var int
     */
    private $opened;

    /**
     * @var int
     */
    private $closed;

    /**
     * @var int
     */
    private $executing;

    /**
     * @return int
     */
    public function getOpened(): int
    {
        return $this->opened;
    }

    /**
     * @param int $opened
     */
    public function setOpened(int $opened): void
    {
        $this->opened = $opened;
    }

    /**
     * @return int
     */
    public function getClosed(): int
    {
        return $this->closed;
    }

    /**
     * @param int $closed
     */
    public function setClosed(int $closed): void
    {
        $this->closed = $closed;
    }

    /**
     * @return int
     */
    public function getExecuting(): int
    {
        return $this->executing;
    }

    /**
     * @param int $executing
     */
    public function setExecuting(int $executing): void
    {
        $this->executing = $executing;
    }
}
