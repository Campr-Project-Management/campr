<?php

namespace AppBundle\Services;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackage;
use Doctrine\ORM\EntityManager;

class WBSService
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getData(Project $project)
    {
        return [
            'id' => $project->getId(),
            'name' => (string) $project,
            'children' => $this->getProjectChildren($project),
            'progress' => $project->getProgress(),
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
                    return $wp->isPhase() && !$wp->getParent() ||
                        $wp->isTutorial() ||
                        ($wp->isTask() && !$wp->getPhase() && !$wp->getMilestone() && !$wp->getParent());
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
        $startDate = array_reduce(
            [$wp->getActualStartAt(), $wp->getForecastStartAt(), $wp->getScheduledStartAt()],
            function ($carry, $item) {
                if ($carry) {
                    return $carry;
                }

                return $item;
            },
            null
        );
        $endDate = array_reduce(
            [$wp->getActualFinishAt(), $wp->getForecastFinishAt(), $wp->getScheduledFinishAt()],
            function ($carry, $item) {
                if ($carry) {
                    return $carry;
                }

                return $item;
            },
            null
        );

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
            'progress' => $wp->getProgress(),
            'puid' => $wp->getPUIDForDisplay(),
            'startDate' => $startDate ? $startDate->format('m/d/Y') : 'N/A',
            'endDate' => $endDate ? $endDate->format('m/d/Y') : 'N/A',
        ];

        switch ($wp->getType()) {
            case WorkPackage::TYPE_PHASE:
                $out['children'] = $wp
                    ->getProject()
                    ->getWorkPackages()
                    ->filter(function (WorkPackage $package) use ($wp) {
                        return (WorkPackage::TYPE_PHASE === $package->getType() && $package->getParent() === $wp)
                            || (WorkPackage::TYPE_MILESTONE === $package->getType() && $package->getPhase() === $wp && !$package->getParent())
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
                        return (WorkPackage::TYPE_MILESTONE === $package->getType() && $package->getParent() === $wp)
                            || (WorkPackage::TYPE_TASK === $package->getType() && $package->getMilestone() === $wp && !$package->getParent())
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
