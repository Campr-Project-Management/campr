<?php

namespace AppBundle\Tests\Component\Project\ModuleAnalysis;

use Component\ProjectModule\Analysis\ProjectInnovationDegreeModuleAnalyser;
use Component\ProjectModule\ProjectModules;

class ProjectInnovationDegreeModuleAnalyserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProjectInnovationDegreeModuleAnalyser
     */
    private $analyser;

    protected function setUp()
    {
        $this->analyser = new ProjectInnovationDegreeModuleAnalyser();
    }

    protected function tearDown()
    {
        $this->analyser = null;
    }

    public function testAnalyseContract()
    {
        $actual = $this->analyser->analyse(
            ProjectModules::MODULE_CONTRACT,
            ProjectInnovationDegreeModuleAnalyser::VALUE_ENHANCEMENTS
        );
        $this->assertTrue($actual);

        $actual = $this->analyser->analyse(
            ProjectModules::MODULE_CONTRACT,
            ProjectInnovationDegreeModuleAnalyser::VALUE_STRATEGIC_INNOVATION
        );
        $this->assertTrue($actual);

        $actual = $this->analyser->analyse(
            ProjectModules::MODULE_CONTRACT,
            ProjectInnovationDegreeModuleAnalyser::VALUE_RADICAL_INNOVATION
        );
        $this->assertTrue($actual);

        $actual = $this->analyser->analyse(
            ProjectModules::MODULE_CONTRACT,
            ProjectInnovationDegreeModuleAnalyser::VALUE_INCREMENTAL
        );
        $this->assertTrue($actual);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAnalyseThrowsAnExceptionForInvalidValues()
    {
        $this->analyser->analyse(ProjectModules::MODULE_CONTRACT, 10);
    }
}
