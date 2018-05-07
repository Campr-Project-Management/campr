<?php

namespace Component\Snapshot\Transformer;

use AppBundle\Entity\Project;
use AppBundle\Entity\Risk;
use AppBundle\Repository\RiskRepository;
use Component\Project\Calculator\RiskTotalCalculator;
use Doctrine\Common\Collections\ArrayCollection;

class RisksTransformer extends AbstractTransformer
{
    /**
     * @var RiskTotalCalculator
     */
    private $riskTotalCalculator;

    /**
     * @var TransformerInterface
     */
    private $dateTransformer;

    /**
     * @var RiskRepository
     */
    private $riskRepository;

    /**
     * RisksTransformer constructor.
     *
     * @param RiskTotalCalculator  $riskTotalCalculator
     * @param TransformerInterface $dateTransformer
     * @param RiskRepository       $riskRepository
     */
    public function __construct(
        RiskTotalCalculator $riskTotalCalculator,
        TransformerInterface $dateTransformer,
        RiskRepository $riskRepository
    ) {
        $this->riskTotalCalculator = $riskTotalCalculator;
        $this->dateTransformer = $dateTransformer;
        $this->riskRepository = $riskRepository;
    }

    /**
     * @param Project $project
     *
     * @return mixed
     */
    protected function doTransform($project)
    {
        $total = $this->riskTotalCalculator->calculate($project);

        return [
            'total' => [
                'potentialCost' => $total->getPotentialCost(),
                'potentialDelay' => $total->getPotentialDelay(),
                'measuresCost' => $total->getMeasuresCost(),
                'measuresCount' => $total->getMeasuresCount(),
            ],
            'items' => $this->getItems($project->getRisks()),
            'topItem' => $this->getTopRisk($project),
            'grid' => $this->riskRepository->getGridCount($project->getId()),
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
    private function getTopRisk(Project $project)
    {
        $risk = $project->getTopRisk();
        if (!$risk) {
            return null;
        }

        return $this->getItem($risk);
    }

    /**
     * @param Risk[]|ArrayCollection $risks
     *
     * @return array
     */
    private function getItems($risks): array
    {
        $items = [];

        foreach ($risks as $risk) {
            $items[] = $this->getItem($risk);
        }

        return $items;
    }

    /**
     * @param Risk $risk
     *
     * @return array
     */
    private function getItem(Risk $risk): array
    {
        return [
            'id' => $risk->getId(),
            'strategyId' => $risk->getRiskStrategyId(),
            'strategyName' => $risk->getRiskStrategyName(),
            'statusId' => $risk->getStatusId(),
            'statusName' => $risk->getStatusName(),
            'title' => $risk->getTitle(),
            'description' => $risk->getDescription(),
            'cost' => $risk->getCost(),
            'delay' => $risk->getDelay(),
            'priority' => $risk->getPriority(),
            'priorityName' => $risk->getPriorityName(),
            'dueDate' => $this->dateTransformer->transform($risk->getDueDate()),
            'impact' => $risk->getImpact(),
            'probability' => $risk->getProbability(),
            'delayUnit' => $risk->getDelayUnit(),
            'potentialCost' => $risk->getPotentialCost(),
            'potentialDelay' => $risk->getPotentialDelay(),
            'potentialDelayHours' => $risk->getPotentialDelayHours(),
            'responsibilityId' => $risk->getResponsibilityId(),
            'responsibilityFullName' => $risk->getResponsibilityFullName(),
            'measures' => [
                'total' => [
                    'count' => count($risk->getMeasures()),
                    'cost' => $risk->getMeasuresTotalCost(),
                ],
            ],
        ];
    }
}
