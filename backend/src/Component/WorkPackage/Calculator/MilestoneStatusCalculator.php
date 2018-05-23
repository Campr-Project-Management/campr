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
    protected function getStatusesCodes(WorkPackage $workPackage): array
    {
        $codes = [];
        foreach ($this->getStatuses() as $status) {
            $count = $this->workPackageRepository->getStatusCountByPhase($workPackage, $status);
            if (!$count) {
                continue;
            }

            $codes[] = $status->getCode();
        }

        return $codes;
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
