<?php

namespace Component\Snapshot;

use AppBundle\Entity\Project;
use Component\Snapshot\Transformer\TransformerInterface;

class ProjectSnapshotFactory
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
    public function create(Project $project): SnapshotInterface
    {
        $data = $this->projectTransformer->transform($project);

        $snapshot = new Snapshot($data);

        return $snapshot;
    }
}
