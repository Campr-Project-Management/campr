<?php

namespace Component\Project\Calculator;

use AppBundle\Entity\Project;
use AppBundle\Repository\WorkPackageRepository;

class WorkPackageTrafficLightTotalCalculator
{
    /**
     * @var WorkPackageRepository
     */
    private $workPackageRepository;

    /**
     * WorkPackageTrafficLightTotalCalculator constructor.
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
     * @return WorkPackageTrafficLightTotal
     */
    public function calculate(Project $project): WorkPackageTrafficLightTotal
    {
        $data = $this->workPackageRepository->getTrafficLightCount($project);

        $total = new WorkPackageTrafficLightTotal();
        $total->setGreen((int) ($data['green'] ?? 0));
        $total->setYellow((int) ($data['yellow'] ?? 0));
        $total->setRed((int) ($data['red'] ?? 0));

        return $total;
    }
}
