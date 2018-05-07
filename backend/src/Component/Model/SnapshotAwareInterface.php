<?php

namespace Component\Model;

use Component\Snapshot\SnapshotInterface;

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
