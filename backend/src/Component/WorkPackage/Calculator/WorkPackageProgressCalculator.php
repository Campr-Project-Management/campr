<?php

namespace Component\WorkPackage\Calculator;

use AppBundle\Entity\WorkPackage;
use AppBundle\Repository\WorkPackageRepository;

class WorkPackageProgressCalculator implements WorkPackageProgressCalculatorInterface
{
    /**
     * @var WorkPackage
     */
    private $workPackageRepository;

    /**
     * @param WorkPackageRepository $workPackageRepository
     */
    public function __construct(WorkPackageRepository $workPackageRepository)
    {
        $this->workPackageRepository = $workPackageRepository;
    }

    /**
     * @param WorkPackage $wp
     *
     * @return int
     */
    public function calculate(WorkPackage $wp): int
    {
        if ($wp->isTask() || $wp->isTutorial()) {
            return $wp->getProgress();
        }

        $workPackages = $this->getChildren($wp);
        if (empty($workPackages)) {
            return 0;
        }

        $workPackageIds = [];
        $workPackages = array_filter(
            $workPackages,
            function (WorkPackage $workPackage) use (&$workPackageIds) {
                if (!in_array($workPackage->getId(), $workPackageIds)) {
                    $workPackageIds[] = $workPackage->getId();

                    return true;
                }

                return false;
            }
        );

        dump($workPackages);

        $progress = array_reduce(
            $workPackages,
            function ($total, WorkPackage $wp) {
                return $total + $wp->getProgress();
            },
            0
        );

        $progress /= count($workPackages);

        return round($progress, 0);
    }

    /**
     * @param WorkPackage $wp
     *
     * @return array
     */
    private function getChildren(WorkPackage $wp)
    {
        if ($wp->isPhase()) {
            return $this->getPhaseWorkPackages($wp);
        }

        if ($wp->isMilestone()) {
            return $this->getMilestoneWorkPackages($wp);
        }

        return [];
    }

    /**
     * @param WorkPackage $phase
     * @param int         $level
     *
     * @return array
     */
    private function getPhaseWorkPackages(WorkPackage $phase, int $level = 5)
    {
        $workPackages = [];
        foreach ($phase->getPhaseChildren() as $child) {
            if ($child->isTask() && $child->isRoot()) {
                $workPackages[] = $child;
                continue;
            }

            if ($level > 0) {
                if ($child->isPhase() && $level > 0) {
                    $workPackages = array_merge($workPackages, $this->getPhaseWorkPackages($child, --$level));
                    continue;
                }

                if ($child->isMilestone() && $level > 0) {
                    $workPackages = array_merge($workPackages, $this->getMilestoneWorkPackages($child, --$level));
                    continue;
                }
            }
        }

        return $workPackages;
    }

    /**
     * @param WorkPackage $milestone
     * @param int         $level
     *
     * @return array
     */
    private function getMilestoneWorkPackages(WorkPackage $milestone, int $level = 5)
    {
        $workPackages = [];
        foreach ($milestone->getMilestoneChildren() as $child) {
            if ($child->isMilestone() && $level > 0) {
                $workPackages = array_merge($workPackages, $this->getMilestoneWorkPackages($child, --$level));
                continue;
            }

            if ($child->isTask() && $child->isRoot()) {
                $workPackages[] = $child;
                continue;
            }
        }

        return $workPackages;
    }
}
