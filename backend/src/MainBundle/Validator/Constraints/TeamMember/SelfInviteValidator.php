<?php

namespace MainBundle\Validator\Constraints\TeamMember;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SelfInviteValidator extends ConstraintValidator
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

        $user = $this
            ->em
            ->getRepository(User::class)
            ->findOneBy([
                'email' => $value,
            ])
        ;

        if ($user === $constraint->user) {
            $this
                ->context
                ->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
