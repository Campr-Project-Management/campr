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
    private $workPackageTrafficLightTotalCountCalculator;

    /**
     * TasksTransformer constructor.
     *
     * @param ProjectProgressCalculatorInterface     $projectProgressCalculator
     * @param WorkPackageStatusTotalCountCalculator  $workPackageTotalStatusCountCalculator
     * @param WorkPackageTrafficLightTotalCalculator $workPackageTrafficLightTotalCalculator
     */
    public function __construct(
        ProjectProgressCalculatorInterface $projectProgressCalculator,
        WorkPackageStatusTotalCountCalculator $workPackageTotalStatusCountCalculator,
        WorkPackageTrafficLightTotalCalculator $workPackageTrafficLightTotalCalculator
    ) {
        $this->projectProgressCalculator = $projectProgressCalculator;
        $this->workPackageTotalStatusCountCalculator = $workPackageTotalStatusCountCalculator;
        $this->workPackageTrafficLightTotalCountCalculator = $workPackageTrafficLightTotalCalculator;
    }

    /**
     * @param Project $project
     *
     * @return mixed
     */
    protected function doTransform($project)
    {
        $statusCount = $this->workPackageTotalStatusCountCalculator->calculate($project);
        $trafficLightTotal = $this->workPackageTrafficLightTotalCountCalculator->calculate($project);

        return [
            'progress' => $this->projectProgressCalculator->calculate($project),
            'total' => [
                'status' => [
                    'opened' => $statusCount->getOpened(),
                    'executing' => $statusCount->getExecuting(),
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
