<?php

namespace MainBundle\Validator\Constraints\TeamMember;

use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\Validator\Constraint;

class ActiveMember extends Constraint
{
    /** @var Team */
    public $team;

    /** @var User */
    public $user;

    public $message = 'invite.team_member';

    public function __construct($options)
    {
        parent::__construct();

        if ($options['team'] && $options['team'] instanceof Team) {
            $this->team = $options['team'];
        } else {
            throw new MissingOptionsException('Please add one team as option parameter');
        }

        if ($options['user'] && $options['user'] instanceof User) {
            $this->user = $options['user'];
        } else {
            throw new MissingOptionsException('Please add one user as option parameter');
        }
    }
}
