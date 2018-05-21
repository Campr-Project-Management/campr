<?php

namespace AppBundle\Services;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackage;
use Component\Project\Calculator\ProjectProgressCalculatorInterface;
use Component\WorkPackage\Calculator\WorkPackageProgressCalculatorInterface;
use Doctrine\ORM\EntityManager;

class WBSService
{
    private $em;

    private $progressCalculator;

    private $workPackageProgressCalculator;

    public function __construct(
        EntityManager $em,
        ProjectProgressCalculatorInterface $projectProgressCalculator,
        WorkPackageProgressCalculatorInterface $workPackageProgressCalculator
    ) {
        $this->em = $em;
        $this->progressCalculator = $projectProgressCalculator;
        $this->workPackageProgressCalculator = $workPackageProgressCalculator;
    }

    public function getData(Project $project)
    {
        return [
            'id' => $project->getId(),
            'name' => (string) $project,
            'children' => $this->getProjectChildren($project),
            'progress' => round($this->progressCalculator->calculate($project)),
            'colorStatus' => $project->getColorStatusId(),
            'colorStatusName' => $project->getColorStatusName(),
            'colorStatusColor' => $project->getColorStatusColor(),
        ];
    }

    /**
     * @param Project $project
     *
     * @return array
     */
    private function getProjectChildren(Project $project)
    {
        return $project
            ->getWorkPackages()
            ->filter(
                function (WorkPackage $wp) {
                    return !$wp->getPhase() && !$wp->getMilestone() && !$wp->getParent();
                }
            )
            ->map(
                function (WorkPackage $wp) {
                    return $this->getChildren($wp);
                }
            )
            ->getValues()
        ;
    }

    private function getChildren(WorkPackage $wp)
    {
        $out = [
            'id' => $wp->getId(),
            'project' => $wp->getProjectId(),
            'phase' => $wp->getPhaseId(),
            'phaseName' => $wp->getPhaseName(),
            'milestone' => $wp->getMilestoneId(),
            'milestoneName' => $wp->getMilestoneName(),
            'parent' => $wp->getParentId(),
            'parentName' => $wp->getParentName(),
            'workPackageStatus' => $wp->getWorkPackageStatusId(),
            'workPackageStatusName' => $wp->getWorkPackageStatusName(),
            'name' => (string) $wp,
            'children' => [],
            'type' => $wp->getType(),
            'colorStatus' => $wp->getColorStatusId(),
            'colorStatusName' => $wp->getColorStatusName(),
            'colorStatusColor' => $wp->getColorStatusColor(),
            'progress' => $this->workPackageProgressCalculator->calculate($wp),
            'puid' => $wp->getPUIDForDisplay(),
            'startDate' => $wp->getActualStartAt() ? $wp->getActualStartAt()->format('d/m/Y') : 'N/A',
            'endDate' => $wp->getActualFinishAt() ? $wp->getActualFinishAt()->format('d/m/Y') : 'N/A',
        ];

        switch ($wp->getType()) {
            case WorkPackage::TYPE_PHASE:
                $out['children'] = $wp
                    ->getProject()
                    ->getWorkPackages()
                    ->filter(function (WorkPackage $package) use ($wp) {
                        return ($package->isTask() && $package->getPhase() === $wp && !$package->getMilestone() && !$package->getParent())
                            || ($package->isPhase() && $package->getParent() === $wp)
                            || ($package->isMilestone() && $package->getPhase() === $wp && !$package->getParent())
                        ;
                    })
                    ->map(function (WorkPackage $wp) {
                        return $this->getChildren($wp);
                    })
                    ->getValues()
                ;
                break;
            case WorkPackage::TYPE_MILESTONE:
                $out['children'] = $wp
                    ->getProject()
                    ->getWorkPackages()
                    ->filter(function (WorkPackage $package) use ($wp) {
                        return ($package->isMilestone() && $package->getParent() === $wp)
                            || ($package->isTask() && $package->getMilestone() === $wp && !$package->getParent())
                        ;
                    })
                    ->map(function (WorkPackage $wp) {
                        return $this->getChildren($wp);
                    })
                    ->getValues()
                ;
                break;
            case WorkPackage::TYPE_TASK:
                $out['children'] = $wp
                    ->getProject()
                    ->getWorkPackages()
                    ->filter(function (WorkPackage $package) use ($wp) {
                        return !$package->isSubtask() && $package->isTask() && $package->getParent() === $wp;
                    })
                    ->map(function (WorkPackage $wp) {
                        return $this->getChildren($wp);
                    })
                    ->getValues()
                ;
                break;
        }

        return $out;
    }
}
