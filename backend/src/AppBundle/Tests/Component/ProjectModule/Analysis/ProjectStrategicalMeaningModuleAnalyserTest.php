<?php

namespace AppBundle\Tests\Component\Project\ModuleAnalysis;

use Component\ProjectModule\Analysis\ProjectStrategicalMeaningModuleAnalyser;
use Component\ProjectModule\ProjectModules;

class ProjectStrategicalMeaningModuleAnalyserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProjectStrategicalMeaningModuleAnalyser
     */
    private $analyser;

    protected function setUp()
    {
        $this->analyser = new ProjectStrategicalMeaningModuleAnalyser();
    }

    protected function tearDown()
    {
        $this->analyser = null;
    }

    public function testAnalyseContract()
    {
        $actual = $this->analyser->analyse(
            ProjectModules::MODULE_CONTRACT,
            ProjectStrategicalMeaningModuleAnalyser::VALUE_LOW
        );
        $this->assertTrue($actual);

        $actual = $this->analyser->analyse(
            ProjectModules::MODULE_CONTRACT,
            ProjectStrategicalMeaningModuleAnalyser::VALUE_MEDIUM
        );
        $this->assertTrue($actual);

        $actual = $this->analyser->analyse(
            ProjectModules::MODULE_CONTRACT,
            ProjectStrategicalMeaningModuleAnalyser::VALUE_HIGH
        );
        $this->assertTrue($actual);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAnalyseThrowsAnExceptionForInvalidValues()
    {
        $this->analyser->analyse(ProjectModules::MODULE_CONTRACT, 3);
    }
}
