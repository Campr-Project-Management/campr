<?php

namespace AppBundle\Event;

use AppBundle\Entity\Project;
use Symfony\Component\EventDispatcher\Event;

class ProjectEvent extends Event
{
    /**
     * @var Project
     */
    private $project;

    /**
     * ProjectEvent constructor.
     *
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }
}
