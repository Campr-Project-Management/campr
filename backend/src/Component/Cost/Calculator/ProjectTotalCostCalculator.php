<?php

namespace Component\Cost\Calculator;

use AppBundle\Entity\Project;
use AppBundle\Repository\CostRepository;
use AppBundle\Repository\WorkPackageRepository;

class ProjectTotalCostCalculator implements ProjectTotalCostCalculatorInterface
{
    /**
     * @var WorkPackageRepository
     */
    private $workPackageRepository;

    /**
     * @var CostRepository
     */
    private $costRepository;

    /**
     * ProjectTotalCostCalculator constructor.
     *
     * @param WorkPackageRepository $workPackageRepository
     * @param CostRepository        $costRepository
     */
    public function __construct(WorkPackageRepository $workPackageRepository, CostRepository $costRepository)
    {
        $this->workPackageRepository = $workPackageRepository;
        $this->costRepository = $costRepository;
    }

    /**
     * @param Project $project
     *
     * @return ProjectTotalCost
     */
    public function calculate(Project $project): ProjectTotalCost
    {
        $data = $this->workPackageRepository->getTotalExternalInternalCosts($project);

        $total = new ProjectTotalCost();
        $total->setInternalActual($data['internal']['actual']);
        $total->setInternalForecast($data['internal']['forecast']);

        $total->setExternalActual($data['external']['actual']);
        $total->setExternalForecast($data['external']['forecast']);

        $data = $this->costRepository->getTotalBaseCost($project);
        $total->setInternalBase($data['internal']);
        $total->setExternalBase($data['external']);

        return $total;
    }
}
