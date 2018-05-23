<?php

namespace Component\WorkPackage\Calculator;

use AppBundle\Entity\WorkPackage;

class MilestoneStatusCalculator extends PhaseStatusCalculator
{
    /**
     * @param WorkPackage $workPackage
     *
     * @return array
     */
    protected function getStatusesCounts(WorkPackage $workPackage)
    {
        $data = [];
        foreach ($this->getStatuses() as $status) {
            $data[$status->getCode()] = $this->workPackageRepository->getStatusCountByMilestone($workPackage, $status);
        }

        return $data;
    }

    /**
     * @param WorkPackage $workPackage
     *
     * @return bool
     */
    protected function isSupported(WorkPackage $workPackage)
    {
        return $workPackage->isMilestone();
    }
}
