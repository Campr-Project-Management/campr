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
        if (null === $xmlNode) {
            $xmlNode = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Task></Task>');
        }

        $xmlNode->addChild('UID', $package->getId());
        $xmlNode->addChild('ID', $package->getId());
        $xmlNode->addChild('Name', $package->getName());
        $this->addCDataChild($xmlNode, 'Notes', $package->getContent());
        $xmlNode->addChild('Start', $package->getScheduledStartAt() ? $package->getScheduledStartAt()->format('Y-m-d H:i:s') : '');
        $xmlNode->addChild('Finish', $package->getScheduledFinishAt() ? $package->getScheduledFinishAt()->format('Y-m-d H:i:s') : '');
        $xmlNode->addChild('ActualStart', $package->getActualStartAt() ? $package->getActualStartAt()->format('Y-m-d H:i:s') : '');
        $xmlNode->addChild('ActualFinish', $package->getActualStartAt() ? $package->getActualStartAt()->format('Y-m-d H:i:s') : '');
        $xmlNode->addChild('Cost', $package->getInternalForecastCost());
        $xmlNode->addChild('ActualCost', $package->getInternalActualCost());

        return $xmlNode;
    }

    private function addCDataChild(\SimpleXMLElement $xmlNode, string $name, ?string $content)
    {
        $newNode = $xmlNode->addChild($name);
        $element = dom_import_simplexml($newNode);
        $element->appendChild(
            $element
                ->ownerDocument
                ->createCDATASection($content)
        );

        return $newNode;
    }
}
