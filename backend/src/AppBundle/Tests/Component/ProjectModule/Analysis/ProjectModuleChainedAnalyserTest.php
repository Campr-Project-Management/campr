<?php

namespace AppBundle\Tests\Component\Project\ModuleAnalysis;

use Component\ProjectModule\Analysis\ProjectBudgetModuleAnalyser;
use Component\ProjectModule\Analysis\ProjectDurationModuleAnalyser;
use Component\ProjectModule\Analysis\ProjectInnovationDegreeModuleAnalyser;
use Component\ProjectModule\Analysis\ProjectMembersModuleAnalyser;
use Component\ProjectModule\Analysis\ProjectModuleChainedAnalyser;
use Component\ProjectModule\Analysis\ProjectModuleAnalyserInterface;
use Component\ProjectModule\Analysis\ProjectStrategicalMeaningModuleAnalyser;
use Component\ProjectModule\Analysis\ProjectTechnologyComplexityModuleAnalyser;
use Component\ProjectModule\ProjectModules;

class ProjectModuleChainedAnalyserTest extends \PHPUnit_Framework_TestCase
{
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

    /**
     * @var ProjectModuleAnalyserInterface[]
     */
    private $analysers;

    protected function setUp()
    {
        $this->durationAnalyser = new ProjectDurationModuleAnalyser();
        $this->budgetAnalyser = new ProjectBudgetModuleAnalyser();
        $this->membersAnalyser = new ProjectMembersModuleAnalyser();
        $this->strategicalAnalyser = new ProjectStrategicalMeaningModuleAnalyser();
        $this->innovationAnalyser = new ProjectInnovationDegreeModuleAnalyser();
        $this->technologyAnalyser = new ProjectTechnologyComplexityModuleAnalyser();

        $this->analysers = [
            $this->durationAnalyser,
            $this->budgetAnalyser,
            $this->membersAnalyser,
            $this->strategicalAnalyser,
            $this->innovationAnalyser,
            $this->technologyAnalyser,
        ];

        $this->analyser = new ProjectModuleChainedAnalyser(...$this->analysers);
    }

    protected function tearDown()
    {
        $this->analyser = null;
        $this->analysers = null;
        $this->durationAnalyser = null;
        $this->budgetAnalyser = null;
        $this->membersAnalyser = null;
        $this->strategicalAnalyser = null;
        $this->innovationAnalyser = null;
        $this->technologyAnalyser = null;
    }

    public function testAnalyseContract()
    {
        $actual = $this->analyser->analyse(
            ProjectModules::MODULE_CONTRACT,
            ProjectStrategicalMeaningModuleAnalyser::VALUE_LOW,
            $this->strategicalAnalyser->getType()
        );
        $this->assertTrue($actual);

        $actual = $this->analyser->analyse(
            ProjectModules::MODULE_CONTRACT,
            ProjectStrategicalMeaningModuleAnalyser::VALUE_MEDIUM,
            $this->strategicalAnalyser->getType()
        );
        $this->assertTrue($actual);

        $actual = $this->analyser->analyse(
            ProjectModules::MODULE_CONTRACT,
            ProjectStrategicalMeaningModuleAnalyser::VALUE_HIGH,
            $this->strategicalAnalyser->getType()
        );
        $this->assertTrue($actual);
    }

    public function testAnalyseRASCI()
    {
        $actual = $this->analyser->analyse(
            ProjectModules::MODULE_RASCI_MATRIX,
            ProjectStrategicalMeaningModuleAnalyser::VALUE_LOW,
            $this->strategicalAnalyser->getType()
        );
        $this->assertFalse($actual);

        $actual = $this->analyser->analyse(
            ProjectModules::MODULE_RASCI_MATRIX,
            ProjectStrategicalMeaningModuleAnalyser::VALUE_MEDIUM,
            $this->strategicalAnalyser->getType()
        );
        $this->assertTrue($actual);

        $actual = $this->analyser->analyse(
            ProjectModules::MODULE_RASCI_MATRIX,
            ProjectStrategicalMeaningModuleAnalyser::VALUE_HIGH,
            $this->strategicalAnalyser->getType()
        );
        $this->assertTrue($actual);
    }

    public function testAnalyseWBS()
    {
        $actual = $this->analyser->analyse(
            ProjectModules::MODULE_WBS,
            ProjectStrategicalMeaningModuleAnalyser::VALUE_LOW,
            $this->strategicalAnalyser->getType()
        );
        $this->assertFalse($actual);

        $actual = $this->analyser->analyse(
            ProjectModules::MODULE_WBS,
            ProjectStrategicalMeaningModuleAnalyser::VALUE_MEDIUM,
            $this->strategicalAnalyser->getType()
        );
        $this->assertFalse($actual);

        $actual = $this->analyser->analyse(
            ProjectModules::MODULE_WBS,
            ProjectStrategicalMeaningModuleAnalyser::VALUE_HIGH,
            $this->strategicalAnalyser->getType()
        );
        $this->assertFalse($actual);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAnalyseThrowsAnExceptionForInvalidValues()
    {
        $this->analyser->analyse(ProjectModules::MODULE_CONTRACT, 3, $this->strategicalAnalyser->getType());
    }
}
