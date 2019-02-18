<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class WorkPackageAssignments extends Constraint
{
    /**
     * @var string
     */
    public $alreadyUsedMessage = 'work_package.assignment.already_used';

    /**
     * @var string
     */
    public $rasciUserMessage = 'work_package.assignment.rasci_user';

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
