<?php

namespace Component\Snapshot\Transformer;

use AppBundle\Entity\Opportunity;
use AppBundle\Entity\Project;
use AppBundle\Repository\OpportunityRepository;
use Component\Project\Calculator\OpportunityTotalCalculator;
use Doctrine\Common\Collections\ArrayCollection;

class OpportunitiesTransformer extends AbstractTransformer
{
    /**
     * @var OpportunityTotalCalculator
     */
    private $opportunityTotalCalculator;

    /**
     * @var TransformerInterface
     */
    private $dateTransformer;

    /**
     * @var OpportunityRepository
     */
    private $opportunityRepository;

    /**
     * RisksTransformer constructor.
     *
     * @param OpportunityTotalCalculator $opportunityTotalCalculator
     * @param TransformerInterface       $dateTransformer
     * @param OpportunityRepository      $opportunityRepository
     */
    public function __construct(
        OpportunityTotalCalculator $opportunityTotalCalculator,
        TransformerInterface $dateTransformer,
        OpportunityRepository $opportunityRepository
    ) {
        $this->opportunityTotalCalculator = $opportunityTotalCalculator;
        $this->dateTransformer = $dateTransformer;
        $this->opportunityRepository = $opportunityRepository;
    }

    /**
     * @param Project $project
     *
     * @return mixed
     */
    protected function doTransform($project)
    {
        $total = $this->opportunityTotalCalculator->calculate($project);

        return [
            'total' => [
                'potentialCost' => $total->getPotentialCost(),
                'potentialTime' => $total->getPotentialTime(),
                'measuresCost' => $total->getMeasuresCost(),
                'measuresCount' => $total->getMeasuresCount(),
            ],
            'items' => $this->getItems($project->getOpportunities()),
            'topItem' => $this->getTopOpportunity($project),
        ];
    }

    /**
     * @param mixed $object
     *
     * @return bool
     */
    public function support($object): bool
    {
        return $object instanceof Project;
    }

    /**
     * @param Project $project
     *
     * @return array|null
     */
    private function getTopOpportunity(Project $project)
    {
        $opportunity = $project->getTopOpportunity();
        if (!$opportunity) {
            return null;
        }

        return $this->getItem($opportunity);
    }

    /**
     * @param Opportunity[]|ArrayCollection $opportunities
     *
     * @return array
     */
    private function getItems($opportunities): array
    {
        $items = [];

        foreach ($opportunities as $opportunity) {
            $items[] = $this->getItem($opportunity);
        }

        return $items;
    }

    /**
     * @param Opportunity $opportunity
     *
     * @return array
     */
    private function getItem(Opportunity $opportunity): array
    {
        return [
            'id' => $opportunity->getId(),
            'strategyId' => $opportunity->getOpportunityStatusId(),
            'strategyName' => $opportunity->getOpportunityStrategyName(),
            'statusId' => $opportunity->getOpportunityStatusId(),
            'statusName' => $opportunity->getOpportunityStatusName(),
            'title' => $opportunity->getTitle(),
            'description' => $opportunity->getDescription(),
            'cost' => $opportunity->getCostSavings(),
            'time' => $opportunity->getTimeSavings(),
            'priority' => $opportunity->getPriority(),
            'priorityName' => $opportunity->getPriorityName(),
            'dueDate' => $this->dateTransformer->transform($opportunity->getDueDate()),
            'impact' => $opportunity->getImpact(),
            'impactIndex' => $opportunity->getImpactIndex(),
            'probability' => $opportunity->getProbability(),
            'probabilityIndex' => $opportunity->getProbabilityIndex(),
            'timeUnit' => $opportunity->getTimeUnit(),
            'potentialCost' => $opportunity->getPotentialCostSavings(),
            'potentialTime' => $opportunity->getPotentialTimeSavings(),
            'potentialTimeHours' => $opportunity->getPotentialTimeSavingsHours(),
            'responsibilityId' => $opportunity->getResponsibilityId(),
            'responsibilityFullName' => $opportunity->getResponsibilityFullName(),
            'measures' => [
                'total' => [
                    'count' => count($opportunity->getMeasures()),
                    'cost' => $opportunity->getMeasuresTotalCost(),
                ],
            ],
        ];
    }
}
