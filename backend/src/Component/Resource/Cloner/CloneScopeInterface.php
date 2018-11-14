<?php

namespace Component\Resource\Cloner;

use Component\Resource\Model\ResourceInterface;

interface CloneScopeInterface
{
    /**
     * @param ResourceInterface $object
     *
     * @return bool
     */
    public function isInScope(ResourceInterface $object): bool;
}
