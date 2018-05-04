<?php

namespace Component\Cost\Graph;

use AppBundle\Entity\Cost;
use AppBundle\Entity\Project;
use AppBundle\Repository\CostRepository;
use AppBundle\Repository\WorkPackageRepository;
use Webmozart\Assert\Assert;

class CostByPhaseGraphDataGenerator
{
    /**
     * @var CostRepository
     */
    private $costRepository;

    /**
     * @var WorkPackageRepository
     */
    private $workPackageRepository;

    /**
     * CostByPhaseGraphDataGenerator constructor.
     *
     * @param CostRepository        $costRepository
     * @param WorkPackageRepository $workPackageRepository
     */
    public function __construct(CostRepository $costRepository, WorkPackageRepository $workPackageRepository)
    {
        $this->costRepository = $costRepository;
        $this->workPackageRepository = $workPackageRepository;
    }

    /**
     * @param Project $project
     * @param int     $costType
     *
     * @return CostGraphData
     */
    public function generate(Project $project, int $costType): CostGraphData
    {
        Assert::true(in_array($costType, [Cost::TYPE_INTERNAL, Cost::TYPE_EXTERNAL]), 'Invalid cost type');

        $baseCosts = $this->costRepository->getTotalBaseCostByPhase($project, $costType);
        $actualCosts = $this->workPackageRepository->getTotalActualCostsByPhase($project, $costType);

        $data = [];
        foreach (array_merge($baseCosts, $actualCosts) as $cost) {
            $phaseName = $cost['phaseName'];
            unset($cost['phaseName']);

            if (!isset($data[$phaseName])) {
                $data[$phaseName] = [
                    'base' => 0,
                    'actual' => 0,
                    'forecast' => 0,
                    'remaining' => 0,
                ];
            }

            $data[$phaseName] = array_merge($data[$phaseName], $cost);
        }

        $graph = new CostGraphData();
        foreach ($data as $phaseName => $values) {
            $graph->setBase($phaseName, $values['base']);
            $graph->setForecast($phaseName, $values['forecast']);
            $graph->setActual($phaseName, $values['actual']);
            $graph->setRemaining($phaseName, $values['base'] - $values['actual']);
        }

        return $graph;
    }
}
