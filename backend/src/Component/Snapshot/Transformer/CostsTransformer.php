<?php

namespace Component\Snapshot\Transformer;

use AppBundle\Entity\Project;
use AppBundle\Entity\Cost;
use Component\Cost\Calculator\ProjectTotalCostCalculatorInterface;
use Component\Cost\Graph\CostByDepartmentGraphDataGenerator;
use Component\Cost\Graph\CostByPhaseGraphDataGenerator;

class CostsTransformer extends AbstractTransformer
{
    /**
     * @var CostByDepartmentGraphDataGenerator
     */
    private $costByDepartmentGraphDataGenerator;

    /**
     * @var CostByPhaseGraphDataGenerator
     */
    private $costByPhaseGraphDataGenerator;

    /**
     * @var ProjectTotalCostCalculatorInterface
     */
    private $projectTotalCostCalculator;

    /**
     * CostsTransformer constructor.
     *
     * @param CostByDepartmentGraphDataGenerator  $costByDepartmentGraphDataGenerator
     * @param CostByPhaseGraphDataGenerator       $costByPhaseGraphDataGenerator
     * @param ProjectTotalCostCalculatorInterface $projectTotalCostCalculator
     */
    public function __construct(
        CostByDepartmentGraphDataGenerator $costByDepartmentGraphDataGenerator,
        CostByPhaseGraphDataGenerator $costByPhaseGraphDataGenerator,
        ProjectTotalCostCalculatorInterface $projectTotalCostCalculator
    ) {
        $this->costByDepartmentGraphDataGenerator = $costByDepartmentGraphDataGenerator;
        $this->costByPhaseGraphDataGenerator = $costByPhaseGraphDataGenerator;
        $this->projectTotalCostCalculator = $projectTotalCostCalculator;
    }

    /**
     * @param Project $project
     *
     * @return mixed
     */
    protected function doTransform($project)
    {
        $internalByPhaseData = $this->costByPhaseGraphDataGenerator
            ->generate($project, Cost::TYPE_INTERNAL)
            ->getData()
        ;
        $externalByPhaseData = $this->costByPhaseGraphDataGenerator
            ->generate($project, Cost::TYPE_EXTERNAL)
            ->getData()
        ;

        $internalByDepartment = $this->costByDepartmentGraphDataGenerator
            ->generate($project, Cost::TYPE_INTERNAL)
            ->getData()
        ;
        $externalByDepartment = $this->costByDepartmentGraphDataGenerator
            ->generate($project, Cost::TYPE_EXTERNAL)
            ->getData()
        ;

        $total = $this->projectTotalCostCalculator->calculate($project);

        return [
            'progress' => $total->getProgress(),
            'total' => [
                'trafficLight' => $total->getTrafficLight(),
            ],
            'internal' => [
                'total' => [
                    'base' => $total->getInternalBase(),
                    'actual' => $total->getInternalActual(),
                    'forecast' => $total->getInternalForecast(),
                    'trafficLight' => $total->getInternalTrafficLight(),
                ],
                'graphs' => [
                    'byPhase' => $internalByPhaseData,
                    'byDepartment' => $internalByDepartment,
                ],
            ],
            'external' => [
                'total' => [
                    'base' => $total->getExternalBase(),
                    'actual' => $total->getExternalActual(),
                    'forecast' => $total->getExternalForecast(),
                    'trafficLight' => $total->getExternalTrafficLight(),
                ],
                'graphs' => [
                    'byPhase' => $externalByPhaseData,
                    'byDepartment' => $externalByDepartment,
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
