<?php

namespace MainBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class TeamSlugUsable extends Constraint
{
    public $message = 'validation.constraints.team.slug.used';

    protected $team;

    public function __construct($options)
    {
        if (isset($options['team'])) {
            $this->team = $options['team'];
        }
    }

    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @return string
     */
    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}
