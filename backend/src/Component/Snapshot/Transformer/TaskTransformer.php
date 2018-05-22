<?php

namespace Component\Snapshot\Transformer;

use AppBundle\Entity\WorkPackage;
use Component\WorkPackage\Calculator\WorkPackageProgressCalculatorInterface;

class TaskTransformer extends AbstractTransformer
{
    /**
     * @var TransformerInterface
     */
    private $dateTransformer;

    /**
     * @var WorkPackageProgressCalculatorInterface
     */
    private $progressCalculator;

    /**
     * WorkPackageTransformer constructor.
     *
     * @param TransformerInterface                   $dateTransformer
     * @param WorkPackageProgressCalculatorInterface $progressCalculator
     */
    public function __construct(
        TransformerInterface $dateTransformer,
        WorkPackageProgressCalculatorInterface $progressCalculator
    ) {
        $this->dateTransformer = $dateTransformer;
        $this->progressCalculator = $progressCalculator;
    }

    /**
     * @param WorkPackage $wp
     *
     * @return array
     */
    protected function doTransform($wp): array
    {
        return [
            'id' => $wp->getId(),
            'name' => $wp->getName(),
            'parentId' => $wp->getParentId(),
            'parentName' => $wp->getParentName(),
            'colorStatusId' => $wp->getColorStatusId(),
            'responsibilityId' => $wp->getResponsibilityId(),
            'responsibilityFullName' => $wp->getResponsibilityFullName(),
            'puid' => $wp->getPuid(),
            'progress' => $this->progressCalculator->calculate($wp),
            'scheduledStartAt' => $this->dateTransformer->transform($wp->getScheduledStartAt()),
            'scheduledFinishAt' => $this->dateTransformer->transform($wp->getScheduledFinishAt()),
            'scheduledDurationDays' => $wp->getScheduledDurationDays(),
            'forecastStartAt' => $this->dateTransformer->transform($wp->getForecastStartAt()),
            'forecastFinishAt' => $this->dateTransformer->transform($wp->getForecastFinishAt()),
            'forecastDurationDays' => $wp->getForecastDurationDays(),
            'actualStartAt' => $this->dateTransformer->transform($wp->getActualStartAt()),
            'actualFinishAt' => $this->dateTransformer->transform($wp->getActualFinishAt()),
            'actualDurationDays' => $wp->getActualDurationDays(),
            'workPackageStatusId' => $wp->getWorkPackageStatusId(),
            'workPackageStatusName' => $wp->getWorkPackageStatusName(),
            'workPackageStatusCode' => $wp->getWorkPackageStatusCode(),
            'type' => $wp->getType(),
            'phaseId' => $wp->getPhaseId(),
            'phaseName' => $wp->getPhaseName(),
            'milestoneId' => $wp->getMilestoneId(),
            'milestoneName' => $wp->getMilestoneName(),
            'externalActualCost' => $wp->getExternalActualCost(),
            'externalForecastCost' => $wp->getExternalForecastCost(),
            'internalActualCost' => $wp->getInternalActualCost(),
            'internalForecastCost' => $wp->getInternalForecastCost(),
            'accountabilityId' => $wp->getAccountabilityId(),
            'accountabilityFullName' => $wp->getAccountabilityFullName(),
        ];
    }

    /**
     * @param WorkPackage $object
     *
     * @return bool
     */
    public function support($object): bool
    {
        return $object instanceof WorkPackage;
    }
}
