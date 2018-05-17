<?php

namespace Component\Project\Calculator;

use AppBundle\Entity\Project;
use AppBundle\Repository\MeasureRepository;
use AppBundle\Repository\OpportunityRepository;

class OpportunityTotalCalculator
{
    /**
     * @var OpportunityRepository
     */
    private $opportunityRepository;

    /**
     * @var MeasureRepository
     */
    private $measureRepository;

    /**
     * RiskTotalCalculator constructor.
     *
     * @param OpportunityRepository $opportunityRepository
     * @param MeasureRepository     $measureRepository
     */
    public function __construct(OpportunityRepository $opportunityRepository, MeasureRepository $measureRepository)
    {
        $this->opportunityRepository = $opportunityRepository;
        $this->measureRepository = $measureRepository;
    }

    /**
     * @param Project $project
     *
     * @return OpportunityTotal
     */
    public function calculate(Project $project): OpportunityTotal
    {
        $data = $this->opportunityRepository->getStatsByProject($project);
        $total = new OpportunityTotal();

        $total->setPotentialCost((float) $data['costSavings']);
        $total->setPotentialTime((float) $data['timeSaving']);

        $data = $this->measureRepository->getStatsForOpportunity($project);
        $total->setMeasuresCount((int) $data['measuresNumber']);
        $total->setMeasuresCost((float) $data['totalCost']);

        return $total;
    }
}
