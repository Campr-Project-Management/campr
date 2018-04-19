<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\User;
use AppBundle\Entity\WorkPackage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class WorkPackageAssignmentsValidator extends ConstraintValidator
{
    /**
     * @param WorkPackage                       $wp         The value that should be validated
     * @param WorkPackageAssignments|Constraint $constraint The constraint for the validation
     */
    public function validate($wp, Constraint $constraint)
    {
        $this->validateResponsability($wp, $constraint);
        $this->validateAccountability($wp, $constraint);
        $this->validateSupportUsers($wp, $constraint);
        $this->validateConsultedUsers($wp, $constraint);
        $this->validateInformedUsers($wp, $constraint);
    }

    /**
     * @param WorkPackage            $wp
     * @param WorkPackageAssignments $constraint
     *
     * @return bool
     */
    private function validateResponsability(WorkPackage $wp, WorkPackageAssignments $constraint): bool
    {
        $user = $wp->getResponsibility();
        if (!$user) {
            return true;
        }

        $ids = $this->getAssignmentsIds($wp);
        $count = count(array_intersect($ids, [$user->getId()]));
        if ($count <= 1) {
            return true;
        }

        $this
            ->context
            ->buildViolation($constraint->message)
            ->atPath('responsibility')
            ->setParameter('%name%', $user->getFullName())
            ->addViolation()
        ;

        return false;
    }

    /**
     * @param WorkPackage            $wp
     * @param WorkPackageAssignments $constraint
     *
     * @return bool
     */
    private function validateAccountability(WorkPackage $wp, WorkPackageAssignments $constraint): bool
    {
        $user = $wp->getAccountability();
        if (!$user) {
            return true;
        }

        $ids = $this->getAssignmentsIds($wp);
        $count = count(array_intersect($ids, [$user->getId()]));
        if ($count <= 1) {
            return true;
        }

        $this
            ->context
            ->buildViolation($constraint->message)
            ->atPath('accountability')
            ->setParameter('%name%', $user->getFullName())
            ->addViolation()
        ;

        return false;
    }

    /**
     * @param WorkPackage            $wp
     * @param WorkPackageAssignments $constraint
     *
     * @return bool
     */
    private function validateSupportUsers(WorkPackage $wp, WorkPackageAssignments $constraint): bool
    {
        $users = $wp->getSupportUsers();
        if (!count($users)) {
            return true;
        }

        $ids = $this->getAssignmentsIds($wp);
        $valid = true;
        foreach ($users as $user) {
            $count = count(array_intersect($ids, [$user->getId()]));
            if ($count <= 1) {
                continue;
            }

            $this
                ->context
                ->buildViolation($constraint->message)
                ->atPath('supportUsers')
                ->setParameter('%name%', $user->getFullName())
                ->addViolation()
            ;

            $valid = false;
        }

        return $valid;
    }

    /**
     * @param WorkPackage            $wp
     * @param WorkPackageAssignments $constraint
     *
     * @return bool
     */
    private function validateConsultedUsers(WorkPackage $wp, WorkPackageAssignments $constraint): bool
    {
        $users = $wp->getConsultedUsers();
        if (!count($users)) {
            return true;
        }

        $ids = $this->getAssignmentsIds($wp);
        $valid = true;
        foreach ($users as $user) {
            $count = count(array_intersect($ids, [$user->getId()]));
            if ($count <= 1) {
                continue;
            }

            $this
                ->context
                ->buildViolation($constraint->message)
                ->atPath('consultedUsers')
                ->setParameter('%name%', $user->getFullName())
                ->addViolation()
            ;

            $valid = false;
        }

        return $valid;
    }

    /**
     * @param WorkPackage            $wp
     * @param WorkPackageAssignments $constraint
     *
     * @return bool
     */
    private function validateInformedUsers(WorkPackage $wp, WorkPackageAssignments $constraint): bool
    {
        $users = $wp->getInformedUsers();
        if (!count($users)) {
            return true;
        }

        $ids = $this->getAssignmentsIds($wp);
        $valid = true;
        foreach ($users as $user) {
            $count = count(array_intersect($ids, [$user->getId()]));
            if ($count <= 1) {
                continue;
            }

            $this
                ->context
                ->buildViolation($constraint->message)
                ->atPath('informedUsers')
                ->setParameter('%name%', $user->getFullName())
                ->addViolation()
            ;

            $valid = false;
        }

        return $valid;
    }

    /**
     * @param WorkPackage $wp
     *
     * @return array
     */
    private function getAssignmentsIds(WorkPackage $wp): array
    {
        $ids = [];
        if ($wp->getResponsibility()) {
            $ids[] = $wp->getResponsibility()->getId();
        }

        if ($wp->getAccountability()) {
            $ids[] = $wp->getAccountability()->getId();
        }

        $ids = array_merge(
            $ids,
            $wp
                ->getSupportUsers()
                ->map(
                    function (User $user) {
                        return $user->getId();
                    }
                )
                ->toArray()
        );

        $ids = array_merge(
            $ids,
            $wp
                ->getInformedUsers()
                ->map(
                    function (User $user) {
                        return $user->getId();
                    }
                )
                ->toArray()
        );

        $ids = array_merge(
            $ids,
            $wp
                ->getConsultedUsers()
                ->map(
                    function (User $user) {
                        return $user->getId();
                    }
                )
                ->toArray()
        );

        return $ids;
    }
}
