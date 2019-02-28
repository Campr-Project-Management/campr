<?php

namespace Component\Resource\Cloner;

use Component\Project\ProjectAwareInterface;
use Component\Project\ProjectInterface;
use Component\Resource\Model\ResourceInterface;

class ProjectCloneScope implements CloneScopeInterface
{
    /**
     * @var ProjectInterface
     */
    private $project;

    /**
     * ProjectCloneScope constructor.
     *
     * @param ProjectInterface $project
     */
    public function __construct(ProjectInterface $project)
    {
        $this->project = $project;
    }

    /**
     * @param ResourceInterface $object
     *
     * @return bool
     */
    public function isInScope(ResourceInterface $object): bool
    {
        if (!($object instanceof ProjectAwareInterface)) {
            return true;
        }

        $project = $object->getProject();
        if (!$project) {
            return false;
        }

        return $project->getId() === $this->project->getId();
    }
}
