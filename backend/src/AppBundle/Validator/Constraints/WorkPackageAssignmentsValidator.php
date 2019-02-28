<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\User;
use AppBundle\Entity\WorkPackage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

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

        if (!$this->isRASCIUser($wp, $user)) {
            $this
                ->context
                ->buildViolation($constraint->rasciUserMessage)
                ->atPath('responsibility')
                ->setParameter('%name%', $user->getFullName())
                ->addViolation();

            return false;
        }

        $ids = $this->getAssignmentsIds($wp);
        $count = count(array_intersect($ids, [$user->getId()]));
        if ($count <= 1) {
            return true;
        }

        $this
            ->context
            ->buildViolation($constraint->alreadyUsedMessage)
            ->atPath('responsibility')
            ->setParameter('%name%', $user->getFullName())
            ->addViolation();

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

        if (!$this->isRASCIUser($wp, $user)) {
            $this
                ->context
                ->buildViolation($constraint->rasciUserMessage)
                ->atPath('accountability')
                ->setParameter('%name%', $user->getFullName())
                ->addViolation();

            return false;
        }

        $ids = $this->getAssignmentsIds($wp);
        $count = count(array_intersect($ids, [$user->getId()]));
        if ($count <= 1) {
            return true;
        }

        $this
            ->context
            ->buildViolation($constraint->alreadyUsedMessage)
            ->atPath('accountability')
            ->setParameter('%name%', $user->getFullName())
            ->addViolation();

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
            if (!$this->isRASCIUser($wp, $user)) {
                $this
                    ->context
                    ->buildViolation($constraint->rasciUserMessage)
                    ->atPath('supportUsers')
                    ->setParameter('%name%', $user->getFullName())
                    ->addViolation();
                $valid = false;
            }

            $count = count(array_intersect($ids, [$user->getId()]));
            if ($count <= 1) {
                continue;
            }

            $this
                ->context
                ->buildViolation($constraint->alreadyUsedMessage)
                ->atPath('supportUsers')
                ->setParameter('%name%', $user->getFullName())
                ->addViolation();

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
            if (!$this->isRASCIUser($wp, $user)) {
                $this
                    ->context
                    ->buildViolation($constraint->rasciUserMessage)
                    ->atPath('consultedUsers')
                    ->setParameter('%name%', $user->getFullName())
                    ->addViolation();
                $valid = false;
            }

            $count = count(array_intersect($ids, [$user->getId()]));
            if ($count <= 1) {
                continue;
            }

            $this
                ->context
                ->buildViolation($constraint->alreadyUsedMessage)
                ->atPath('consultedUsers')
                ->setParameter('%name%', $user->getFullName())
                ->addViolation();

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
            if (!$this->isRASCIUser($wp, $user)) {
                $this
                    ->context
                    ->buildViolation($constraint->rasciUserMessage)
                    ->atPath('informedUsers')
                    ->setParameter('%name%', $user->getFullName())
                    ->addViolation();
                $valid = false;
            }

            $count = count(array_intersect($ids, [$user->getId()]));
            if ($count <= 1) {
                continue;
            }

            $this
                ->context
                ->buildViolation($constraint->alreadyUsedMessage)
                ->atPath('informedUsers')
                ->setParameter('%name%', $user->getFullName())
                ->addViolation();

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

    /**
     * @param WorkPackage $wp
     * @param User        $user
     *
     * @return bool
     */
    private function isRASCIUser(WorkPackage $wp, User $user): bool
    {
        $project = $wp->getProject();
        Assert::notNull($project, 'Task project is not set');

        $projectUser = $user->getProjectUser($project);
        Assert::notEmpty($projectUser, 'Project user is not set');

        return $projectUser->isRASCI();
    }
}
