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
            'children' => $this->getPhases($project),
            'colorStatus' => $project->getColorStatusId(),
            'colorStatusName' => $project->getColorStatusName(),
            'colorStatusColor' => $project->getColorStatusColor(),
        ];
    }

    /**
     * I know this could be refactored into the getChildren() but i'll do it later.
     * I promise!
     */
    private function getPhases(Project $project)
    {
        return $project
            ->getWorkPackages()
            ->filter(function (WorkPackage $wp) {
                return $wp->getType() === WorkPackage::TYPE_PHASE && !$wp->getParent();
            })
            ->map(function (WorkPackage $wp) {
                return $this->getChildren($wp);
            })
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
            'name' => (string) $wp,
            'children' => [],
            'type' => $wp->getType(),
            'colorStatus' => $wp->getColorStatusId(),
            'colorStatusName' => $wp->getColorStatusName(),
            'colorStatusColor' => $wp->getColorStatusColor(),
            'progress' => $wp->getProgress(),
            'puid' => $wp->getPuid(),
            'startDate' => $startDate ? $startDate->format('m/d/Y') : 'N/A',
            'endDate' => $endDate ? $endDate->format('m/d/Y') : 'N/A',
        ];

        switch ($wp->getType()) {
            case WorkPackage::TYPE_PHASE:
                $out['children'] = $wp
                    ->getProject()
                    ->getWorkPackages()
                    ->filter(function (WorkPackage $package) use ($wp) {
                        return in_array($package->getType(), [WorkPackage::TYPE_PHASE, WorkPackage::TYPE_MILESTONE])
                            && ($package->getParent() === $wp || $package->getPhase() === $wp)
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
                        return in_array($package->getType(), [WorkPackage::TYPE_MILESTONE, WorkPackage::TYPE_TASK])
                            && ($package->getParent() === $wp || $package->getMilestone() === $wp)
                            && !$package->getParent()
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
                        return in_array($package->getType(), [WorkPackage::TYPE_TASK])
                            && $package->getParent() === $wp
                        ;
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
