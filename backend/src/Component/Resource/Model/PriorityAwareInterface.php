<?php

namespace Component\Resource\Model;

interface PriorityAwareInterface
{
    /**
     * @param int $priority
     */
    public function setPriority(int $priority = null);

    /**
     * @return int
     */
    public function getPriority();
}
