<?php

namespace Component\WorkPackage\Calculator;

use AppBundle\Entity\WorkPackage;

interface DateRangeCalculatorInterface
{
    /**
     * @param WorkPackage $workPackage
     *
     * @return \DateTime[]
     */
    public function calculate(WorkPackage $workPackage): array;
}
