<?php

declare(strict_types=1);

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Exception;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class AtLeastOneAdminValidator extends ConstraintValidator
{
    /** @var UserRepository */
    private $userRepository;

    /**
     * AtLeastOneAdminValidator constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param mixed           $value
     * @param AtLeastOneAdmin $constraint
     *
     * @throws Exception
     */
    public function validate($value, Constraint $constraint): void
    {
        $rolesIntersection = array_intersect(
            $value->getRoles(),
            [
                User::ROLE_ADMIN,
                User::ROLE_SUPER_ADMIN,
            ]
        );

        if (0 === count($rolesIntersection) && 0 === $this->userRepository->countAdminsExcept($value)) {
            $this
                ->context
                ->buildViolation($constraint->message)
                ->atPath('roles')
                ->addViolation()
            ;
        }
    }
}
