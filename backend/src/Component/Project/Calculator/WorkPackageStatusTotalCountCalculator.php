<?php

namespace Component\Project\Calculator;

use AppBundle\Entity\Project;
use AppBundle\Repository\WorkPackageRepository;

class WorkPackageStatusTotalCountCalculator
{
    /**
     * @var WorkPackageRepository
     */
    private $workPackageRepository;

    /**
     * WorkPackageStatusTotalCountCalculator constructor.
     *
     * @param WorkPackageRepository $workPackageRepository
     */
    public function __construct(WorkPackageRepository $workPackageRepository)
    {
        $this->workPackageRepository = $workPackageRepository;
    }

    /**
     * @param Project $project
     *
     * @return WorkPackageStatusTotalCount
     */
    public function calculate(Project $project): WorkPackageStatusTotalCount
    {
        $count = new WorkPackageStatusTotalCount();

        $opened = $this->workPackageRepository->getTotalOpenedCount($project);
        $closed = $this->workPackageRepository->getTotalClosedCount($project);

        $count->setOpened($opened);
        $count->setClosed($closed);

        return $count;
    }
}
