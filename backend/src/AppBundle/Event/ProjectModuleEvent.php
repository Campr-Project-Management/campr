<?php

namespace AppBundle\Event;

use AppBundle\Entity\ProjectModule;
use Symfony\Component\EventDispatcher\Event;

class ProjectModuleEvent extends Event
{
    /**
     * @var ProjectModule
     */
    private $projectModule;

    /**
     * ProjectModuleEvent constructor.
     *
     * @param ProjectModule $projectModule
     */
    public function __construct(ProjectModule $projectModule)
    {
        $this->projectModule = $projectModule;
    }

    /**
     * @return ProjectModule
     */
    public function getProjectModule(): ProjectModule
    {
        return $this->projectModule;
    }
}
