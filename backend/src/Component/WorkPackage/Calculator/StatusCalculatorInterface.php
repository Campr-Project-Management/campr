<?php

namespace Component\WorkPackage\Calculator;

use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\WorkPackageStatus;

interface StatusCalculatorInterface
{
    /**
     * @param WorkPackage $workPackage
     *
     * @return WorkPackageStatus
     */
    public function calculate(WorkPackage $workPackage): WorkPackageStatus;
}
