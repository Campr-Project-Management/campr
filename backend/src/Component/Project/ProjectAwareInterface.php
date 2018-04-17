<?php

namespace Component\Project;

interface ProjectAwareInterface
{
    /**
     * @param ProjectInterface $project
     */
    public function setProject(ProjectInterface $project);

    /**
     * @return ProjectInterface
     */
    public function getProject();
}
