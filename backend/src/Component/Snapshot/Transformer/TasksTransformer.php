<?php

namespace Component\Snapshot\Transformer;

use AppBundle\Entity\Project;
use Component\Project\Calculator\ProjectProgressCalculatorInterface;
use Component\Project\Calculator\WorkPackageTrafficLightTotalCalculator;
use Component\Project\Calculator\WorkPackageStatusTotalCountCalculator;

class TasksTransformer extends AbstractTransformer
{
    /**
     * @var ProjectProgressCalculatorInterface
     */
    private $projectProgressCalculator;

    /**
     * @var WorkPackageStatusTotalCountCalculator
     */
    private $workPackageTotalStatusCountCalculator;

    /**
     * @var WorkPackageTrafficLightTotalCalculator
     */
    private $workPackageConditionTotalCountCalculator;

    /**
     * TasksTransformer constructor.
     *
     * @param ProjectProgressCalculatorInterface     $projectProgressCalculator
     * @param WorkPackageStatusTotalCountCalculator  $workPackageTotalStatusCountCalculator
     * @param WorkPackageTrafficLightTotalCalculator $workPackageConditionTotalCountCalculator
     */
    public function __construct(
        ProjectProgressCalculatorInterface $projectProgressCalculator,
        WorkPackageStatusTotalCountCalculator $workPackageTotalStatusCountCalculator,
        WorkPackageTrafficLightTotalCalculator $workPackageConditionTotalCountCalculator
    ) {
        $this->projectProgressCalculator = $projectProgressCalculator;
        $this->workPackageTotalStatusCountCalculator = $workPackageTotalStatusCountCalculator;
        $this->workPackageConditionTotalCountCalculator = $workPackageConditionTotalCountCalculator;
    }

    /**
     * @param Project $project
     *
     * @return mixed
     */
    protected function doTransform($project)
    {
        $statusCount = $this->workPackageTotalStatusCountCalculator->calculate($project);
        $trafficLightTotal = $this->workPackageConditionTotalCountCalculator->calculate($project);

        return [
            'progress' => $this->projectProgressCalculator->calculate($project),
            'total' => [
                'status' => [
                    'opened' => $statusCount->getOpened(),
                    'closed' => $statusCount->getClosed(),
                ],
                'trafficLight' => [
                    'green' => $trafficLightTotal->getGreen(),
                    'yellow' => $trafficLightTotal->getYellow(),
                    'red' => $trafficLightTotal->getRed(),
                ],
            ],
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
}
