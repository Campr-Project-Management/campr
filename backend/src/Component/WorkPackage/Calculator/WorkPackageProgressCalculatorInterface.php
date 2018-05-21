<?php

namespace Component\WorkPackage\Calculator;

use AppBundle\Entity\WorkPackage;

interface WorkPackageProgressCalculatorInterface
{
    /**
     * @param WorkPackage $workPackage
     *
     * @return int
     */
    public function calculate(WorkPackage $workPackage): int;
}
