<?php

namespace Component\Project\Calculator;

use AppBundle\Entity\Project;
use Component\Date\DateRangeInterface;

interface DateRangeCalculatorInterface
{
    /**
     * @param Project $project
     *
     * @return DateRangeInterface
     */
    public function calculate(Project $project): DateRangeInterface;
}
