<?php

namespace MainBundle\Validator\Constraints\TeamMember;

use AppBundle\Entity\User;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\Validator\Constraint;

class SelfInvite extends Constraint
{
    /** @var User */
    public $user;

    public $message = 'invite.yourself';

    public function __construct($options)
    {
        parent::__construct();

        if ($options['user'] && $options['user'] instanceof User) {
            $this->user = $options['user'];
        } else {
            throw new MissingOptionsException('Please add one user as option parameter');
        }
    }
}
