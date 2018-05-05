<?php

namespace Component\Project\Calculator;

use AppBundle\Entity\Project;
use AppBundle\Repository\WorkPackageRepository;

class ProjectCostProgressCalculator implements ProjectProgressCalculatorInterface
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
        $data = $this->workPackageRepository->getTotalExternalInternalCosts($project);
        $actual = (float) $data['actual'] ?? 0;
        $forecast = (float) $data['forecast'] ?? 0;

        if (!$forecast) {
            return 0;
        }

        return round(($actual / $forecast) * 100, 2);
    }
}
