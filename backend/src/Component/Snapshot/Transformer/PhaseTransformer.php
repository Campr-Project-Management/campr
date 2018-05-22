<?php

namespace Component\Snapshot\Transformer;

use AppBundle\Entity\WorkPackage;
use Component\WorkPackage\Calculator\DateRangeCalculatorInterface;
use Component\WorkPackage\Calculator\StatusCalculatorInterface;

class PhaseTransformer extends AbstractTransformer
{
    /**
     * @var TransformerInterface
     */
    private $workPackageTransformer;

    /**
     * @var DateRangeCalculatorInterface
     */
    private $forecastDatesCalculator;

    /**
     * @var DateRangeCalculatorInterface
     */
    private $actualDatesCalculator;

    /**
     * @var TransformerInterface
     */
    private $dateTransformer;

    /**
     * @var StatusCalculatorInterface
     */
    private $statusCalculator;

    /**
     * PhaseTransformer constructor.
     *
     * @param TransformerInterface         $workPackageTransformer
     * @param TransformerInterface         $dateTransformer
     * @param DateRangeCalculatorInterface $forecastDatesCalculator
     * @param DateRangeCalculatorInterface $actualDatesCalculator
     * @param StatusCalculatorInterface    $statusCalculator
     */
    public function __construct(
        TransformerInterface $workPackageTransformer,
        TransformerInterface $dateTransformer,
        DateRangeCalculatorInterface $forecastDatesCalculator,
        DateRangeCalculatorInterface $actualDatesCalculator,
        StatusCalculatorInterface $statusCalculator
    ) {
        $this->workPackageTransformer = $workPackageTransformer;
        $this->forecastDatesCalculator = $forecastDatesCalculator;
        $this->actualDatesCalculator = $actualDatesCalculator;
        $this->dateTransformer = $dateTransformer;
        $this->statusCalculator = $statusCalculator;
    }

    /**
     * @param WorkPackage $wp
     *
     * @return array
     */
    protected function doTransform($wp): array
    {
        $data = $this->workPackageTransformer->transform($wp);

        $data = array_intersect_key(
            $data,
            array_fill_keys(
                [
                    'id',
                    'name',
                    'parentId',
                    'parentName',
                    'responsibilityId',
                    'responsibilityFullName',
                    'progress',
                    'scheduledStartAt',
                    'scheduledFinishAt',
                    'scheduledDurationDays',
                    'phaseId',
                    'phaseName',
                    'accountabilityId',
                    'accountabilityFullName',
                ],
                null
            )
        );

        $range = $this->forecastDatesCalculator->calculate($wp);
        $data['forecastStartAt'] = $this->dateTransformer->transform($range->getStart());
        $data['forecastFinishAt'] = $this->dateTransformer->transform($range->getFinish());
        $data['forecastDurationDays'] = $range->getDurationDays();

        $range = $this->actualDatesCalculator->calculate($wp);
        $data['actualStartAt'] = $this->dateTransformer->transform($range->getStart());
        $data['actualFinishAt'] = $this->dateTransformer->transform($range->getFinish());
        $data['actualDurationDays'] = $range->getDurationDays();

        $status = $this->statusCalculator->calculate($wp);
        $data['workPackageStatusId'] = $status->getId();
        $data['workPackageStatusName'] = $status->getName();
        $data['workPackageStatusCode'] = $status->getCode();

        return $data;
    }

    /**
     * @param WorkPackage $wp
     *
     * @return bool
     */
    public function support($wp): bool
    {
        return ($wp instanceof WorkPackage) && $wp->isPhase();
    }
}
