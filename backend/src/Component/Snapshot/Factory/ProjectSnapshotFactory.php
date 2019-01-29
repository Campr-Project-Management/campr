<?php

namespace Component\Snapshot\Factory;

use AppBundle\Entity\Project;
use Component\Snapshot\Snapshot;
use Component\Snapshot\Transformer\TransformerInterface;
use Component\Snapshot\Model\SnapshotInterface;

class ProjectSnapshotFactory implements ProjectSnapshotFactoryInterface
{
    /**
     * @var TransformerInterface
     */
    private $projectTransformer;

    /**
     * ProjectSnapshotFactory constructor.
     *
     * @param TransformerInterface $projectTransformer
     */
    public function __construct(TransformerInterface $projectTransformer)
    {
        $this->projectTransformer = $projectTransformer;
    }

    /**
     * @param Project $project
     *
     * @return SnapshotInterface
     */
    public function createNew(Project $project): SnapshotInterface
    {
        $data = $this->projectTransformer->transform($project);

        $snapshot = new Snapshot($data);

        return $snapshot;
    }
}
