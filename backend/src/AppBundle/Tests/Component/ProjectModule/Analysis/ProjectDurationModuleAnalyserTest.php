<?php

namespace AppBundle\Tests\Component\Project\ModuleAnalysis;

use Component\ProjectModule\ProjectModules;
use Component\ProjectModule\Analysis\ProjectDurationModuleAnalyser;

class ProjectDurationModuleAnalyserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProjectDurationModuleAnalyser
     */
    private $analyser;

    protected function setUp()
    {
        $this->analyser = new ProjectDurationModuleAnalyser();
    }

    protected function tearDown()
    {
        $this->analyser = null;
    }

    public function testAnalyseContract()
    {
        $actual = $this->analyser->analyse(ProjectModules::MODULE_CONTRACT, 4);
        $this->assertTrue($actual);

        $actual = $this->analyser->analyse(ProjectModules::MODULE_CONTRACT, ProjectDurationModuleAnalyser::MIN_VALUE);
        $this->assertTrue($actual);

        $actual = $this->analyser->analyse(ProjectModules::MODULE_CONTRACT, 37);
        $this->assertTrue($actual);
    }

    public function testAnalyseOrganization()
    {
        $actual = $this->analyser->analyse(ProjectModules::MODULE_ORGANIZATION, 4);
        $this->assertTrue($actual);

        $actual = $this->analyser->analyse(ProjectModules::MODULE_ORGANIZATION, ProjectDurationModuleAnalyser::MIN_VALUE);
        $this->assertTrue($actual);

        $actual = $this->analyser->analyse(ProjectModules::MODULE_ORGANIZATION, 37);
        $this->assertTrue($actual);
    }

    public function testAnalysePhasesAndMilestones()
    {
        $actual = $this->analyser->analyse(ProjectModules::MODULE_PHASES_AND_MILESTONES, 9);
        $this->assertFalse($actual);

        $actual = $this->analyser->analyse(ProjectModules::MODULE_PHASES_AND_MILESTONES, ProjectDurationModuleAnalyser::MIN_VALUE);
        $this->assertFalse($actual);

        $actual = $this->analyser->analyse(ProjectModules::MODULE_PHASES_AND_MILESTONES, 36);
        $this->assertTrue($actual);

        $actual = $this->analyser->analyse(ProjectModules::MODULE_PHASES_AND_MILESTONES, 37);
        $this->assertTrue($actual);
    }

    public function testAnalyseRASCI()
    {
        $actual = $this->analyser->analyse(ProjectModules::MODULE_RASCI_MATRIX, 9);
        $this->assertFalse($actual);
    }

    public function testAnalyseMeeting()
    {
        $actual = $this->analyser->analyse(ProjectModules::MODULE_MEETINGS, 2);
        $this->assertFalse($actual);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAnalyseThrowsAnExceptionForInvalidValues()
    {
        $this->analyser->analyse(ProjectModules::MODULE_CONTRACT, -44);
    }
}
