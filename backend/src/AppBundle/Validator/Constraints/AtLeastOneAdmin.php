<?php

declare(strict_types=1);

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AtLeastOneAdmin extends Constraint
{
    public $message = 'You cannot remove the admin role from the last admin of the workspace.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
