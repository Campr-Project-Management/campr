<?php

namespace Component\WorkPackage\Calculator;

use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\WorkPackageStatus;
use Webmozart\Assert\Assert;

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
            $count = $this->workPackageRepository->getStatusCountByMilestone($workPackage, $status);
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

    /**
     * @param WorkPackage $workPackage
     *
     * @return WorkPackageStatus
     */
    public function calculate(WorkPackage $workPackage): WorkPackageStatus
    {
        Assert::true($this->isSupported($workPackage), 'WorkPackage is not a milestone');

        $status = $this->calculateStatus($workPackage);

        switch (true) {
            case $status instanceof WorkPackageStatus:
                $workPackage->setStatusGenerated(true);

                return $status;
            default:
                return $workPackage->getWorkPackageStatus()
                    ? $workPackage->getWorkPackageStatus()
                    : $this->workPackageStatusRepository->getDefault();
        }
    }
}
