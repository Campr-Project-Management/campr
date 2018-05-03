<?php

namespace Component\Model;

interface PriorityAwareInterface
{
    /**
     * @param int|string|null $priority
     */
    public function setPriority($priority = null);

    /**
     * @return int|string|null
     */
    public function getPriority();
}
