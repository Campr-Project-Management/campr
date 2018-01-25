<?php

namespace MainBundle\Validator\Constraints\TeamMember;

use AppBundle\Entity\TeamInvite;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UserInvitedValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        if (empty($value)) {
            return;
        }

        $teamInvite = $this
            ->em
            ->getRepository(TeamInvite::class)
            ->findOneBy([
                'email' => $value,
                'team' => $constraint->team,
            ])
        ;

        if ($teamInvite) {
            $this
                ->context
                ->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
