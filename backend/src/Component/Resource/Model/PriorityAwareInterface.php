<?php

namespace Component\Resource\Model;

interface PriorityAwareInterface
{
    /**
     * @param int $priority
     */
    public function setPriority($priority);

    /**
     * @return int
     */
    public function getPriority();
}
