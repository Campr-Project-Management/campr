<?php

namespace MainBundle\Validator\Constraints\TeamMember;

use AppBundle\Entity\Team;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\Validator\Constraint;

class UserInvited extends Constraint
{
    /** @var Team */
    public $team;

    public $message = 'validation.constraints.team_member.invitation.sent';

    public function __construct($options)
    {
        parent::__construct();

        if ($options['team'] && $options['team'] instanceof Team) {
            $this->team = $options['team'];
        } else {
            throw new MissingOptionsException('Please add one team as option parameter');
        }
    }
}
