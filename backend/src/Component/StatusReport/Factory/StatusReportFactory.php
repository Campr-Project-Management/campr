<?php

namespace Component\StatusReport\Factory;

use AppBundle\Entity\Project;
use AppBundle\Entity\StatusReport;
use Component\Resource\Factory\FactoryInterface;
use Component\Snapshot\Factory\ProjectSnapshotFactoryInterface;

class StatusReportFactory implements FactoryInterface
{
    /**
     * @var ProjectSnapshotFactoryInterface
     */
    private $projectSnapshotFactory;

    /**
     * StatusReportFactory constructor.
     *
     * @param ProjectSnapshotFactoryInterface $projectSnapshotFactory
     */
    public function __construct(ProjectSnapshotFactoryInterface $projectSnapshotFactory)
    {
        $this->projectSnapshotFactory = $projectSnapshotFactory;
    }

    /**
     * @return StatusReport
     */
    public function createNew()
    {
        return new StatusReport();
    }

    /**
     * @param Project $project
     *
     * @return StatusReport
     */
    public function createNewWithProject(Project $project): StatusReport
    {
        $statusReport = $this->createNew();
        $snapshot = $this->projectSnapshotFactory->createNew($project);

        $statusReport->setSnapshot($snapshot);
        $statusReport->setProject($project);
        $statusReport->setProjectTrafficLight($project->getTrafficLight());
        $statusReport->setModules($project->getProjectModulesList());

        return $statusReport;
    }
}
