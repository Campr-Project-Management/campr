<?php

namespace AppBundle\Tests\Component\Project\ModuleAnalysis;

use Component\ProjectModule\Analysis\ProjectInnovationDegreeModuleAnalyser;
use Component\ProjectModule\Analysis\ProjectModulesResolver;
use Component\ProjectModule\Analysis\ProjectBudgetModuleAnalyser;
use Component\ProjectModule\Analysis\ProjectDurationModuleAnalyser;
use Component\ProjectModule\Analysis\ProjectMembersModuleAnalyser;
use Component\ProjectModule\Analysis\ProjectModuleChainedAnalyser;
use Component\ProjectModule\Analysis\ProjectStrategicalMeaningModuleAnalyser;
use Component\ProjectModule\Analysis\ProjectTechnologyComplexityModuleAnalyser;
use Component\ProjectModule\ProjectModules;

class ProjectModulesResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProjectModulesResolver
     */
    private $resolver;

    /**
     * @var ProjectStrategicalMeaningModuleAnalyser
     */
    private $analyser;

    /**
     * @var ProjectDurationModuleAnalyser
     */
    private $durationAnalyser;

    /**
     * @var ProjectBudgetModuleAnalyser
     */
    private $budgetAnalyser;

    /**
     * @var ProjectMembersModuleAnalyser
     */
    private $membersAnalyser;

    /**
     * @var ProjectStrategicalMeaningModuleAnalyser
     */
    private $strategicalAnalyser;

    /**
     * @var ProjectInnovationDegreeModuleAnalyser
     */
    private $innovationAnalyser;

    /**
     * @var ProjectTechnologyComplexityModuleAnalyser
     */
    private $technologyAnalyser;

    protected function setUp()
    {
        $this->durationAnalyser = new ProjectDurationModuleAnalyser();
        $this->budgetAnalyser = new ProjectBudgetModuleAnalyser();
        $this->membersAnalyser = new ProjectMembersModuleAnalyser();
        $this->strategicalAnalyser = new ProjectStrategicalMeaningModuleAnalyser();
        $this->innovationAnalyser = new ProjectInnovationDegreeModuleAnalyser();
        $this->technologyAnalyser = new ProjectTechnologyComplexityModuleAnalyser();

        $analysers = [
            $this->durationAnalyser,
            $this->budgetAnalyser,
            $this->membersAnalyser,
            $this->strategicalAnalyser,
            $this->innovationAnalyser,
            $this->technologyAnalyser,
        ];

        $this->analyser = new ProjectModuleChainedAnalyser(...$analysers);
        $this->resolver = new ProjectModulesResolver($this->analyser);
    }

    protected function tearDown()
    {
        $this->analyser = null;
        $this->durationAnalyser = null;
        $this->budgetAnalyser = null;
        $this->membersAnalyser = null;
        $this->strategicalAnalyser = null;
        $this->innovationAnalyser = null;
        $this->technologyAnalyser = null;
        $this->resolver = null;
    }

    public function testResolveWithEmptyData()
    {
        $actuals = $this->resolver->resolve([]);

        $this->assertEmpty($actuals);
    }

    /**
     * @dataProvider getResolveProvider
     *
     * @param array $data
     * @param array $expected
     */
    public function testResolve(array $data, array $expected)
    {
        $actuals = $this->resolver->resolve($data);

        $this->assertEmpty(array_diff($expected, $actuals));
    }

    /**
     * @return array
     */
    public function getResolveProvider(): array
    {
        return [
            [
                'data' => [
                    ProjectDurationModuleAnalyser::TYPE => 4,
                    ProjectBudgetModuleAnalyser::TYPE => 1000000,
                    ProjectMembersModuleAnalyser::TYPE => 20,
                    ProjectStrategicalMeaningModuleAnalyser::TYPE => ProjectStrategicalMeaningModuleAnalyser::VALUE_MEDIUM,
                    ProjectInnovationDegreeModuleAnalyser::TYPE => ProjectInnovationDegreeModuleAnalyser::VALUE_ENHANCEMENTS,
                    ProjectTechnologyComplexityModuleAnalyser::TYPE => ProjectTechnologyComplexityModuleAnalyser::VALUE_LOW,
                ],
                'expected' => [
                    ProjectModules::MODULE_CONTRACT,
                    ProjectModules::MODULE_ORGANIZATION,
                    ProjectModules::MODULE_TASK_MANAGEMENT,
                    ProjectModules::MODULE_RASCI_MATRIX,
                    ProjectModules::MODULE_INTERNAL_COSTS,
                    ProjectModules::MODULE_EXTERNAL_COSTS,
                    ProjectModules::MODULE_MEETINGS,
                    ProjectModules::MODULE_TODOS,
                    ProjectModules::MODULE_INFOS,
                    ProjectModules::MODULE_DECISIONS,
                    ProjectModules::MODULE_STATUS_REPORT,
                    ProjectModules::MODULE_CLOSE_DOWN_PROJECT,
                ],
            ],
            [
                'data' => [
                    ProjectDurationModuleAnalyser::TYPE => 35,
                    ProjectBudgetModuleAnalyser::TYPE => 1000000,
                    ProjectMembersModuleAnalyser::TYPE => 5,
                    ProjectStrategicalMeaningModuleAnalyser::TYPE => ProjectStrategicalMeaningModuleAnalyser::VALUE_MEDIUM,
                    ProjectInnovationDegreeModuleAnalyser::TYPE => ProjectInnovationDegreeModuleAnalyser::VALUE_STRATEGIC_INNOVATION,
                    ProjectTechnologyComplexityModuleAnalyser::TYPE => ProjectTechnologyComplexityModuleAnalyser::VALUE_HIGH,
                ],
                'expected' => [
                    ProjectModules::MODULE_CONTRACT,
                    ProjectModules::MODULE_ORGANIZATION,
                    ProjectModules::MODULE_PHASES_AND_MILESTONES,
                    ProjectModules::MODULE_TASK_MANAGEMENT,
                    ProjectModules::MODULE_GANTT_CHART,
                    ProjectModules::MODULE_RASCI_MATRIX,
                    ProjectModules::MODULE_WBS,
                    ProjectModules::MODULE_INTERNAL_COSTS,
                    ProjectModules::MODULE_EXTERNAL_COSTS,
                    ProjectModules::MODULE_RISKS_AND_OPPORTUNITIES,
                    ProjectModules::MODULE_MEETINGS,
                    ProjectModules::MODULE_TODOS,
                    ProjectModules::MODULE_INFOS,
                    ProjectModules::MODULE_DECISIONS,
                    ProjectModules::MODULE_STATUS_REPORT,
                    ProjectModules::MODULE_CLOSE_DOWN_PROJECT,
                ],
            ],
        ];
    }
}
