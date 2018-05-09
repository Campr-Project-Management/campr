<?php

namespace Component\Cost\Graph;

use AppBundle\Entity\Cost;
use AppBundle\Entity\Project;
use AppBundle\Repository\CostRepository;
use AppBundle\Repository\ProjectUserRepository;
use AppBundle\Repository\WorkPackageRepository;
use Webmozart\Assert\Assert;

class CostByDepartmentGraphDataGenerator
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
     * @var ProjectUserRepository
     */
    private $projectUserRepository;

    /**
     * CostByDepartmentGraphDataGenerator constructor.
     *
     * @param CostRepository        $costRepository
     * @param WorkPackageRepository $workPackageRepository
     * @param ProjectUserRepository $projectUserRepository
     */
    public function __construct(
        CostRepository $costRepository,
        WorkPackageRepository $workPackageRepository,
        ProjectUserRepository $projectUserRepository
    ) {
        $this->costRepository = $costRepository;
        $this->workPackageRepository = $workPackageRepository;
        $this->projectUserRepository = $projectUserRepository;
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

        $userDepartments = $this->projectUserRepository->getUserAndDepartment($project);
        $data = [];
        foreach ($userDepartments as $userDepartment) {
            $data[$userDepartment['department']]['userIds'][] = $userDepartment['uid'];
        }

        foreach ($data as $key => $value) {
            $base = $this->costRepository->getTotalBaseCostByPhase($project, $costType, $value['userIds']);
            $actual = $this->workPackageRepository->getTotalActualCostsByPhase($project, $costType, $value['userIds']);

            $data[$key]['base'] = (float) $base[0]['base'] ?? 0;
            $data[$key]['actual'] = (float) $actual[0]['actual'] ?? 0;
            $data[$key]['forecast'] = (float) $actual[0]['forecast'] ?? 0;
            $data[$key]['remaining'] = $data[$key]['base'] - $data[$key]['actual'];
        }

        $graph = new CostGraphData();
        foreach ($data as $department => $values) {
            $graph->setBase($department, $values['base']);
            $graph->setForecast($department, $values['forecast']);
            $graph->setActual($department, $values['actual']);
            $graph->setRemaining($department, $values['base'] - $values['actual']);
        }

        return $graph;
    }
}
