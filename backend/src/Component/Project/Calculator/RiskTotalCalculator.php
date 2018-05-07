<?php

namespace Component\Project\Calculator;

use AppBundle\Entity\Project;
use AppBundle\Repository\MeasureRepository;
use AppBundle\Repository\RiskRepository;

class RiskTotalCalculator
{
    /**
     * @var RiskRepository
     */
    private $riskRepository;

    /**
     * @var MeasureRepository
     */
    private $measureRepository;

    /**
     * RiskTotalCalculator constructor.
     *
     * @param RiskRepository    $riskRepository
     * @param MeasureRepository $measureRepository
     */
    public function __construct(RiskRepository $riskRepository, MeasureRepository $measureRepository)
    {
        $this->riskRepository = $riskRepository;
        $this->measureRepository = $measureRepository;
    }

    /**
     * @param Project $project
     *
     * @return RiskTotal
     */
    public function calculate(Project $project): RiskTotal
    {
        $data = $this->riskRepository->getStatsByProject($project);
        $total = new RiskTotal();

        $total->setPotentialCost((float) $data['costs']);
        $total->setPotentialDelay((float) $data['delay']);

        $data = $this->measureRepository->getStatsForRisk($project);
        $total->setMeasuresCount((int) $data['measuresNumber']);
        $total->setMeasuresCost((float) $data['totalCost']);

        return $total;
    }
}
