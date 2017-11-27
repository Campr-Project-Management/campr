<?php

namespace AppBundle\Services;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackage;

/**
 * Class ExportService
 * Exports entities in the xml format.
 */
class ExportService
{
    public function xmlToString(\SimpleXMLElement $xmlNode)
    {
        return $xmlNode->asXML();
    }

    public function exportProject(Project $project)
    {
        $xmlNode = new \SimpleXMLElement(
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<Project xmlns="http://schemas.microsoft.com/project"></Project>'
        );

        $xmlNode->addChild('Name', $project->getName());
        if ($project->getApprovedAt()) {
            $xmlNode->addChild(
                'StartDate',
                $project->getApprovedAt()->format(\DateTime::ISO8601)
            );
        }

        $tasks = $xmlNode->addChild('Tasks');

        foreach ($project->getWorkPackages() as $wp) {
            $task = $tasks->addChild('Task');
            $this->exportTask($wp, $task);
        }

        return $xmlNode;
    }

    public function exportTask(WorkPackage $package, \SimpleXMLElement $xmlNode = null)
    {
        if ($xmlNode === null) {
            $xmlNode = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Task></Task>');
        }

        $xmlNode->addChild('UID', $package->getPuid());
        $xmlNode->addChild('ID', $package->getId());
        $xmlNode->addChild('Name', $package->getName());
        $xmlNode->addChild('ExternalTask', $package->getParentName());
        $xmlNode->addChild('EarlyStart', $package->getScheduledStartAt() ? $package->getScheduledStartAt()->format('Y-m-d H:i:s') : '');
        $xmlNode->addChild('EarlyFinish', $package->getScheduledFinishAt() ? $package->getScheduledFinishAt()->format('Y-m-d H:i:s') : '');
        $xmlNode->addChild('LateStart', $package->getForecastStartAt() ? $package->getForecastStartAt()->format('Y-m-d H:i:s') : '');
        $xmlNode->addChild('LateFinish', $package->getForecastFinishAt() ? $package->getForecastFinishAt()->format('Y-m-d H:i:s') : '');
        $xmlNode->addChild('Start', $package->getActualStartAt() ? $package->getActualStartAt()->format('Y-m-d H:i:s') : '');
        $xmlNode->addChild('Finish', $package->getActualFinishAt() ? $package->getActualFinishAt()->format('Y-m-d H:i:s') : '');
        $xmlNode->addChild('Milestone', $package->getIsKeyMilestone() ? 'Yes' : 'No');
        $xmlNode->addChild('Calendar', $package->getCalendarId());
        $xmlNode->addChild('PercentComplete', $package->getProgress());

        $assignementsNode = $xmlNode->addChild('Assignements', $package->getCalendarId());
        foreach ($package->getAssignments() as $assignment) {
            $assignmentNode = $assignementsNode->addChild('Assignement');
            $assignmentNode->addChild('UID', $assignment->getExternalId());
            $assignmentNode->addChild('TaskUID', $package->getPuid());
            $assignmentNode->addChild('ResourceUID', $assignment->getWorkPackageProjectWorkCostTypeName());
            $assignmentNode->addChild('PercentWorkComplete', $assignment->getPercentWorkComplete());
            $assignmentNode->addChild('Milestone', $assignment->getMilestone());
            $assignmentNode->addChild('Confirmed', $assignment->getConfirmed());
            $assignmentNode->addChild('Start', $assignment->getStartedAt() ? $assignment->getStartedAt()->format('Y-m-d H:i:s') : '');
            $assignmentNode->addChild('Finish', $assignment->getFinishedAt() ? $assignment->getFinishedAt()->format('Y-m-d H:i:s') : '');

            $timephasesNode = $assignmentNode->addChild('Timephases');
            foreach ($assignment->getTimephases() as $timephase) {
                $timephaseNode = $timephasesNode->addChild('Timephase');
                $timephaseNode->addChild('UID', $timephase->getId());
                $timephaseNode->addChild('Type', $timephase->getType());
                $timephaseNode->addChild('Unit', $timephase->getUnit());
                $timephaseNode->addChild('Value', $timephase->getValue());
                $timephaseNode->addChild('Start', $timephase->getStartedAt() ? $timephase->getStartedAt()->format('Y-m-d H:i:s') : '');
                $timephaseNode->addChild('Finish', $timephase->getFinishedAt() ? $timephase->getFinishedAt()->format('Y-m-d H:i:s') : '');
            }
        }

        return $xmlNode;
    }
}
