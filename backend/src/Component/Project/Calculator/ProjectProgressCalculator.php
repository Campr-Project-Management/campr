<?php

namespace Component\Project\Calculator;

use AppBundle\Entity\Project;
use AppBundle\Repository\WorkPackageRepository;

class ProjectProgressCalculator implements ProjectProgressCalculatorInterface
{
    /**
     * @var WorkPackageRepository
     */
    private $workPackageRepository;

    /**
     * ProjectCostProgressCalculator constructor.
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
     * @return float
     */
    public function calculate(Project $project): float
    {
        return round($this->workPackageRepository->getProjectProgress($project), 2);
    }
}
