<?php

namespace AppBundle\Model;

use AppBundle\Entity\Team;

class BetaTeam
{
    /**
     * @var Team
     */
    private $team;

    /**
     * @var string
     */
    private $email;

    /**
     * BetaTeam constructor.
     *
     * @param Team   $team
     * @param string $email
     */
    public function __construct(Team $team, string $email)
    {
        $this->team = $team;
        $this->email = $email;
    }

    /**
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param Team $team
     */
    public function setTeam($team)
    {
        $this->team = $team;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
}
