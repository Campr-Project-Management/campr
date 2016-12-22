<?php

namespace MainBundle\Validator\Constraints;

use AppBundle\Entity\TeamSlug;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TeamSlugUsableValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $teamSlug = $this
            ->em
            ->getRepository(TeamSlug::class)
            ->findOneBy([
                'team' => $constraint->getTeam(),
                'slug' => $value,
            ])
        ;

        if ($teamSlug) {
            $this
                ->context
                ->buildViolation($constraint->message)
                ->setParameter('%string%', $value)
                ->addViolation()
            ;
        }
    }
}
