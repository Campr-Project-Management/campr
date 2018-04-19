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
    public $message = 'work_package.assignment.already_used';

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
