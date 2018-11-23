<?php

namespace Component\Project;

interface ProjectAwareInterface
{
    /**
     * @param ProjectInterface|null $project
     */
    public function setProject(ProjectInterface $project = null);

    /**
     * @return ProjectInterface|null
     */
    public function getProject();
}
