<?php

namespace Component\Resource\Model;

use Component\Snapshot\Model\SnapshotInterface;

interface SnapshotAwareInterface
{
    /**
     * @return SnapshotInterface
     */
    public function getSnapshot(): SnapshotInterface;

    /**
     * @param SnapshotInterface $snapshot
     */
    public function setSnapshot(SnapshotInterface $snapshot);
}
