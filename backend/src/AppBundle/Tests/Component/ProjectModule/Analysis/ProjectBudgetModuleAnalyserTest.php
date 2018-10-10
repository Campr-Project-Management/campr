<?php

namespace AppBundle\Tests\Component\Project\ModuleAnalysis;

use Component\ProjectModule\Analysis\ProjectBudgetModuleAnalyser;
use Component\ProjectModule\Analysis\ProjectDurationModuleAnalyser;
use Component\ProjectModule\ProjectModules;

class ProjectBudgetModuleAnalyserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProjectDurationModuleAnalyser
     */
    private $analyser;

    protected function setUp()
    {
        $this->analyser = new ProjectBudgetModuleAnalyser();
    }

    protected function tearDown()
    {
        $this->analyser = null;
    }

    public function testAnalyseContract()
    {
        $actual = $this->analyser->analyse(ProjectModules::MODULE_CONTRACT, 400000);
        $this->assertTrue($actual);

        $actual = $this->analyser->analyse(ProjectModules::MODULE_CONTRACT, ProjectBudgetModuleAnalyser::MIN_VALUE);
        $this->assertTrue($actual);

        $actual = $this->analyser->analyse(ProjectModules::MODULE_CONTRACT, 104000000);
        $this->assertTrue($actual);
    }

    public function testAnalyseOrganization()
    {
        $actual = $this->analyser->analyse(ProjectModules::MODULE_ORGANIZATION, 650000);
        $this->assertTrue($actual);

        $actual = $this->analyser->analyse(ProjectModules::MODULE_ORGANIZATION, ProjectBudgetModuleAnalyser::MIN_VALUE);
        $this->assertTrue($actual);

        $actual = $this->analyser->analyse(ProjectModules::MODULE_ORGANIZATION, 10020000);
        $this->assertTrue($actual);
    }

    public function testAnalysePhasesAndMilestones()
    {
        $actual = $this->analyser->analyse(ProjectModules::MODULE_PHASES_AND_MILESTONES, 20000);
        $this->assertFalse($actual);

        $actual = $this->analyser->analyse(ProjectModules::MODULE_PHASES_AND_MILESTONES, ProjectBudgetModuleAnalyser::MIN_VALUE);
        $this->assertFalse($actual);
    }

    public function testAnalyseRASCI()
    {
        $actual = $this->analyser->analyse(ProjectModules::MODULE_RASCI_MATRIX, 300000);
        $this->assertFalse($actual);

        $actual = $this->analyser->analyse(ProjectModules::MODULE_RASCI_MATRIX, 1000000);
        $this->assertTrue($actual);
    }

    public function testAnalyseInternalCosts()
    {
        $actual = $this->analyser->analyse(ProjectModules::MODULE_INTERNAL_COSTS, 100000);
        $this->assertFalse($actual);

        $actual = $this->analyser->analyse(ProjectModules::MODULE_INTERNAL_COSTS, 150000);
        $this->assertTrue($actual);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAnalyseThrowsAnExceptionForInvalidValues()
    {
        $this->analyser->analyse(ProjectModules::MODULE_CONTRACT, -440000);
    }
}
