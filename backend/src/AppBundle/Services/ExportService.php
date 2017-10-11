<?php

namespace AppBundle\Services;

use AppBundle\Entity\WorkPackage;

/**
 * Class ExportService
 * Exports entities in the xml format.
 */
class ExportService
{
    public function exportTask(WorkPackage $package)
    {
        $xmlNode = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><task></task>');
        $xmlNode->addChild('UID', $package->getPuid());
        $xmlNode->addChild('ID', $package->getId());
        $xmlNode->addChild('Active', 1);
        $xmlNode->addChild('Manual', 0);
        $xmlNode->addChild('Type', $package->getType());
        $xmlNode->addChild('CreateDate', $package->getCreatedAt()->format('Y-m-d H:i:s'));
        $xmlNode->addChild('WBS', 0);
        $xmlNode->addChild('OutlineNumber', 0);
        $xmlNode->addChild('OutlineLevel', 0);
        $xmlNode->addChild('Priority', 0);
        $xmlNode->addChild('Start', $package->getActualStartAt() ? $package->getActualStartAt()->format('Y-m-d H:i:s') : '');
        $xmlNode->addChild('Finish', $package->getActualFinishAt() ? $package->getActualFinishAt()->format('Y-m-d H:i:s') : '');
        $xmlNode->addChild('Duration', $package->getDuration());
        $xmlNode->addChild('Start', '0');
        $xmlNode->addChild('Finish', '0');
        $xmlNode->addChild('Duration', '0');
        $xmlNode->addChild('Work', '0');
        $xmlNode->addChild('ResumeValid', '0');
        $xmlNode->addChild('EffortDriven', '0');
        $xmlNode->addChild('Recurring', '0');
        $xmlNode->addChild('OverAllocated', '0');
        $xmlNode->addChild('Estimated', '0');
        $xmlNode->addChild('Milestone', $package->getMilestoneName());
        $xmlNode->addChild('Summary', '0');
        $xmlNode->addChild('DisplayAsSummary', '0');
        $xmlNode->addChild('Critical', '0');
        $xmlNode->addChild('IsSubproject', '0');
        $xmlNode->addChild('IsSubprojectReadOnly', '0');
        $xmlNode->addChild('ExternalTask', '0');
        $xmlNode->addChild('EarlyStart', '0');
        $xmlNode->addChild('EarlyFinish', '0');
        $xmlNode->addChild('LateStart', '0');
        $xmlNode->addChild('LateFinish', '0');
        $xmlNode->addChild('StartVariance', '0');
        $xmlNode->addChild('FinishVariance', '0');
        $xmlNode->addChild('WorkVariance', '0');
        $xmlNode->addChild('FreeSlack', '0');
        $xmlNode->addChild('TotalSlack', '0');
        $xmlNode->addChild('StartSlack', '0');
        $xmlNode->addChild('FinishSlack', '0');
        $xmlNode->addChild('FixedCost', '0');
        $xmlNode->addChild('FixedCostAccrual', '0');
        $xmlNode->addChild('PercentComplete', $package->getProgress());
        $xmlNode->addChild('PercentWorkComplete', '0');
        $xmlNode->addChild('Cost', '0');
        $xmlNode->addChild('OvertimeCost', '0');
        $xmlNode->addChild('OvertimeWork', '0');
        $xmlNode->addChild('ActualDuration', '0');
        $xmlNode->addChild('ActualCost', '0');
        $xmlNode->addChild('ActualOvertimeCost', '0');
        $xmlNode->addChild('ActualWork', '0');
        $xmlNode->addChild('ActualOvertimeWork', '0');
        $xmlNode->addChild('RegularWork', '0');
        $xmlNode->addChild('RemainingDuration', '0');
        $xmlNode->addChild('RemainingCost', '0');
        $xmlNode->addChild('RemainingWork', '0');
        $xmlNode->addChild('RemainingOvertimeCost', '0');
        $xmlNode->addChild('RemainingOvertimeWork', '0');
        $xmlNode->addChild('ACWP', '0');
        $xmlNode->addChild('CV', '0');
        $xmlNode->addChild('ConstraintType', '0');
        $xmlNode->addChild('CalendarUID', '0');
        $xmlNode->addChild('LevelAssignments', '0');
        $xmlNode->addChild('LevelingCanSplit', '0');
        $xmlNode->addChild('LevelingDelay', '0');
        $xmlNode->addChild('LevelingDelayFormat', '0');
        $xmlNode->addChild('IgnoreResourceCalendar', '0');
        $xmlNode->addChild('HideBar', '0');
        $xmlNode->addChild('Rollup', '0');
        $xmlNode->addChild('BCWS', '0');
        $xmlNode->addChild('BCWP', '0');
        $xmlNode->addChild('PhysicalPercentComplete', '0');
        $xmlNode->addChild('EarnedValueMethod', '0');
        $xmlNode->addChild('IsPublished', '0');
        $xmlNode->addChild('CommitmentType', '0');

        return $xmlNode;
    }
}
