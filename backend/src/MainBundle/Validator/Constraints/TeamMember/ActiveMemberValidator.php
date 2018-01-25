<?php

namespace MainBundle\Validator\Constraints\TeamMember;

use AppBundle\Entity\TeamMember;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ActiveMemberValidator extends ConstraintValidator
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

        if ($user && $user != $constraint->user) {
            $teamMember = $this
                ->em
                ->getRepository(TeamMember::class)
                ->findOneBy([
                    'user' => $user,
                    'team' => $constraint->team,
                ])
            ;

            if ($teamMember) {
                $this
                    ->context
                    ->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}
