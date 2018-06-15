<?php

namespace Component\Snapshot\Model;

interface SnapshotInterface
{
    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime;
}
