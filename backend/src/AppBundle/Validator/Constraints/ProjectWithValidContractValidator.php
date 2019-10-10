<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\WorkPackageStatus;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ProjectWithValidContractValidator extends ConstraintValidator
{
    /**
     * @param mixed                               $value
     * @param ProjectWithValidContract|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (
            (
                $value instanceof Project &&
                $value->getContracts()->count() > 0 &&
                $value->getContracts()->last()->isFrozen() &&
                $value->getContracts()->last()->getApprovedAt() instanceof \DateTime
            ) ||
            $this->isValidContext()
        ) {
            return;
        }

        $this->addViolation($constraint->message);
    }

    private function isValidContext(): bool
    {
        $object = $this->context->getObject();
        switch (true) {
            case $object instanceof WorkPackage:
                /*
                 * from #816:
                 *
                 * When creating a NEW task and setting e.g. progress to e.g. 25 and/ or the task status to 'ongoing',
                 * a pop-up shallappear with the message: Please fill in the Project Contract, approve the project and
                 * freeze the base!
                 */
                if (
                    null !== $object->getId() ||
                    !in_array($object->getWorkPackageStatusCode(), [WorkPackageStatus::CODE_OPEN, WorkPackageStatus::CODE_CLOSED]) ||
                    $object->getProgress() > 0
                ) {
                    return false;
                }
        }

        return true;
    }

    private function addViolation(string $message): void
    {
        $this->context
            ->buildViolation($message)
            ->addViolation()
        ;
    }
}
