<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class WorkPackageScheduledDates extends Constraint
{
    /**
     * @var string
     */
    public $greaterThanOrEqualFinishAtMessage = 'greater_than_or_equal.scheduled_finish_at';

    /**
     * @var string
     */
    public $invalidStartAtMessage = 'invalid.work_package.scheduled_start_at';

    /**
     * @var string
     */
    public $invalidFinishAtMessage = 'invalid.work_package.scheduled_finish_at';

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
