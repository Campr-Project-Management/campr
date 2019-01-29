<?php

namespace Component\Snapshot\Factory;

use AppBundle\Entity\Project;
use Component\Snapshot\Model\SnapshotInterface;

interface ProjectSnapshotFactoryInterface
{
    /**
     * @param Project $project
     *
     * @return SnapshotInterface
     */
    public function createNew(Project $project): SnapshotInterface;
}
