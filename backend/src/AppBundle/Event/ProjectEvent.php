<?php

namespace AppBundle\Event;

use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class ProjectEvent extends Event
{
    /**
     * @var Project
     */
    private $project;

    /**
     * @var string
     */
    private $name;

    /**
     * @var User
     */
    private $user;

    /**
     * ProjectEvent constructor.
     *
     * @param Project     $project
     * @param User|null   $user
     * @param string|null $name
     */
    public function __construct(Project $project, User $user = null, string $name = null)
    {
        $this->project = $project;
        $this->user = $user;
        $this->name = $name;
    }

    /**
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }
}
