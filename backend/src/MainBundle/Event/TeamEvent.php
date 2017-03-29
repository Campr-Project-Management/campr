<?php

namespace MainBundle\Event;

use AppBundle\Entity\Team;
use Symfony\Component\EventDispatcher\Event;

/**
 * The team.created event is dispatched each time a new Team is created.
 */
class TeamEvent extends Event
{
    const CREATED = 'team.created';

    protected $team;

    /**
     * TeamEvent constructor.
     *
     * @param Team $team
     */
    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    /**
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }
}
