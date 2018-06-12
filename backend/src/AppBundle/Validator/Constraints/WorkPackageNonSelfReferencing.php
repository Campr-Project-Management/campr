<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class WorkPackageNonSelfReferencing extends Constraint
{
    public $parent = 'self_reference.work_package.parent';

    public $milestone = 'self_reference.work_package.milestone';

    public $phase = 'self_reference.work_package.phase';

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
