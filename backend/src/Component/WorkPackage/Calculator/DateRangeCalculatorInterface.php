<?php

namespace Component\WorkPackage\Calculator;

use AppBundle\Entity\WorkPackage;
use Component\Date\DateRangeInterface;

interface DateRangeCalculatorInterface
{
    /**
     * @param WorkPackage $workPackage
     *
     * @return DateRangeInterface
     */
    public function calculate(WorkPackage $workPackage): DateRangeInterface;
}
